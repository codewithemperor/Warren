<?php
// verifyPayment.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'vendor/autoload.php'; // Ensure Web3.php is installed via Composer
require 'config.php'; // Database configuration
use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->load();

// Read input data
$data = json_decode(file_get_contents("php://input"), true);
$transactionHash = $data['transaction_hash'];

try {
    // Start a database transaction
    $pdo->beginTransaction();

    // Check if the user is logged in
    if (!isset($_SESSION['user'])) {
        throw new Exception("User not logged in.");
    }

    $userId = $_SESSION['user']['id']; // Get the user ID from the session

    // Fetch admin wallet address
    $walletResponse = file_get_contents("https://warrencol.com/assets/php/getAdminWallet.php");
    $walletData = json_decode($walletResponse, true);

    if (!$walletData || isset($walletData['error'])) {
        throw new Exception($walletData['error']['message'] ?? 'Failed to fetch admin wallet address'); // Ensure the error is a string
    }

    $adminWalletAddress = $walletData['wallet_address'];

    // Fetch transaction details using BscScan API
    $bscscanApiKey = $_ENV['BSCSCAN_API_KEY']; // Add your BscScan API key to .env
    $bscscanUrl = "https://api.bscscan.com/api?module=proxy&action=eth_getTransactionByHash&txhash={$transactionHash}&apikey={$bscscanApiKey}";
    $transactionResponse = file_get_contents($bscscanUrl);
    $transactionData = json_decode($transactionResponse, true);

    if (!$transactionData || isset($transactionData['error'])) {
        $errorMessage = $transactionData['error']['message'] ?? 'Failed to fetch transaction details';
        throw new Exception($errorMessage); // Ensure the error is a string
    }

    $transaction = $transactionData['result'];

    if (!$transaction) {
        throw new Exception("Transaction not found.");
    }

    // Check if the transaction is sent to the correct wallet
    // Normalize the admin wallet address (convert to lowercase and trim whitespace)
    $adminWallet = strtolower(trim($adminWalletAddress));

    $transactionInput = $transaction['input']; // Raw input data from the transaction
    if (substr($transactionInput, 0, 10) === '0xa9059cbb') { // Check if it's a token transfer
        // Extract the recipient address from the input data
        $recipientAddress = '0x' . substr($transactionInput, 34, 40); // Recipient address is at offset 34-74
        $recipientAddress = strtolower(trim($recipientAddress)); // Normalize the address

        // Compare the recipient address with the admin wallet address
        if ($recipientAddress !== $adminWallet) {
            throw new Exception("Transaction not sent to the correct wallet. Expected: {$adminWallet}, Received: {$recipientAddress}");
        }
    } else {
        throw new Exception("Invalid transaction type. Expected a token transfer.");
    }

    // Check if the transaction hash has already been used
    $hashCheckQuery = "SELECT id FROM payments WHERE transaction_hash = :transaction_hash";
    $hashCheckStmt = $pdo->prepare($hashCheckQuery);
    $hashCheckStmt->execute(['transaction_hash' => $transactionHash]);
    if ($hashCheckStmt->fetch()) {
        throw new Exception("Transaction hash has already been used.");
    }

    // Check if the transaction hash has already been used by the current user
    $hashCheckQuery = "SELECT id FROM payments WHERE transaction_hash = :transaction_hash AND user_id = :user_id";
    $hashCheckStmt = $pdo->prepare($hashCheckQuery);
    $hashCheckStmt->execute([
        'transaction_hash' => $transactionHash,
        'user_id' => $userId,
    ]);
    if ($hashCheckStmt->fetch()) {
        throw new Exception("Transaction hash has already been used by this user.");
    }

    // Fetch the price from the database
    $paymentQuery = "SELECT price, package_id FROM payments WHERE user_id = :user_id";
    $paymentStmt = $pdo->prepare($paymentQuery);
    $paymentStmt->execute([
        'user_id' => $userId,
    ]);
    $payment = $paymentStmt->fetch();

    if (!$payment) {
        throw new Exception("Payment record not found.");
    }

    // Debugging: Log the price from the database
    error_log("Price from Database: {$payment['price']}");
    error_log("Package ID from Database: {$payment['package_id']}");

    // Convert the price from tokens to Wei (assuming 18 decimals)
    $priceInTokens = $payment['price']; // e.g., 18.00 tokens
    $expectedAmountInWei = bcmul($priceInTokens, bcpow('10', '18')); // Convert to Wei (18000000000000000000)

    // Debugging: Log the expected amount
    error_log("Expected Amount (Wei): {$expectedAmountInWei}");

    // Extract the actual amount from the transaction input
    if (substr($transactionInput, 0, 10) === '0xa9059cbb') {
        // The amount is encoded in the last 32 bytes of the input (64 characters)
        $amountHex = substr($transactionInput, 74, 64); // Extract the amount part
        $actualAmountInWei = hexdec($amountHex); // Convert from hex to decimal

        // Convert the actual amount to a string without scientific notation
        $actualAmountInWeiStr = number_format($actualAmountInWei, 0, '', '');

        // Debugging: Log the actual amount
        error_log("Actual Amount (Wei): {$actualAmountInWeiStr}");

        // Compare the amounts as strings
        if ($actualAmountInWeiStr !== $expectedAmountInWei) {
            throw new Exception("Transaction amount does not match the expected amount. Expected: {$expectedAmountInWei} Wei, Received: {$actualAmountInWeiStr} Wei");
        }
    } else {
        throw new Exception("Invalid transaction type. Expected a token transfer.");
    }

    // Verify the transaction is not backdated
    $currentTimestamp = time();
    $transactionTimestamp = hexdec($transaction['blockNumber']); // Get block timestamp
    if ($transactionTimestamp > $currentTimestamp) {
        throw new Exception("Transaction timestamp is invalid.");
    }

    // Update payment status to 'completed' and set the transaction hash for the current user
    $updateQuery = "UPDATE payments SET payment_status = 'completed', transaction_hash = :transaction_hash WHERE user_id = :user_id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->execute([
        'user_id' => $userId,
        'transaction_hash' => $transactionHash, // Add the transaction hash to the query
    ]);

    // Fetch package details
    $packageQuery = "SELECT price, validity_days FROM packages WHERE id = :package_id";
    $packageStmt = $pdo->prepare($packageQuery);
    $packageStmt->execute(['package_id' => $payment['package_id']]);
    $package = $packageStmt->fetch();

    if (!$package) {
        throw new Exception("Package not found.");
    }

    // Calculate start and end dates
    $startDate = date('Y-m-d H:i:s');
    $endDate = date('Y-m-d H:i:s', strtotime("+{$package['validity_days']} days"));

    // Insert subscription into the database
    $subscriptionQuery = "
        INSERT INTO subscriptions (user_id, package_id, start_date, end_date, transaction_hash)
        VALUES (:user_id, :package_id, :start_date, :end_date, :transaction_hash)
    ";
    $subscriptionStmt = $pdo->prepare($subscriptionQuery);
    $subscriptionStmt->execute([
        'user_id' => $userId,
        'package_id' => $payment['package_id'],
        'start_date' => $startDate,
        'end_date' => $endDate,
        'transaction_hash' => $transactionHash,
    ]);

    // Update user's is_subscribed status
    $userUpdateQuery = "UPDATE users SET is_subscribed = 1 WHERE id = :user_id";
    $userUpdateStmt = $pdo->prepare($userUpdateQuery);
    $userUpdateStmt->execute(['user_id' => $userId]);

    // Commit the transaction
    $pdo->commit();

    echo json_encode(['message' => 'Payment verified and subscription updated successfully!']);
} catch (Exception $e) {
    // Roll back the transaction on error (only if a transaction is active)
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]); // Ensure the error is a string
}