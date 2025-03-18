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

    // Normalize the transaction 'to' address (convert to lowercase and trim whitespace)
    $transactionToAddress = strtolower(trim($transaction['to']));

    // Compare the normalized addresses
    if ($transactionToAddress !== $adminWallet) {
        throw new Exception("Transaction not sent to the correct wallet. Expected: {$adminWallet}, Received: {$transactionToAddress}");
    }

     // Check if the transaction hash has already been used
     $hashCheckQuery = "SELECT id FROM payments WHERE transaction_hash = :transaction_hash AND payment_status = 'completed'";
     $hashCheckStmt = $pdo->prepare($hashCheckQuery);
     $hashCheckStmt->execute(['transaction_hash' => $transactionHash]);
     if ($hashCheckStmt->fetch()) {
         throw new Exception("Transaction hash has already been used.");
     }

    // Check if the transaction hash has already been used by the current user
    $hashCheckQuery = "SELECT id FROM payments WHERE transaction_hash = :transaction_hash AND user_id = :user_id AND payment_status = 'completed'";
    $hashCheckStmt = $pdo->prepare($hashCheckQuery);
    $hashCheckStmt->execute([
        'transaction_hash' => $transactionHash,
        'user_id' => $userId,
    ]);
    if ($hashCheckStmt->fetch()) {
        throw new Exception("Transaction hash has already been used by this user.");
    }

    // Verify the transaction amount
    $paymentQuery = "SELECT price FROM payments WHERE transaction_hash = :transaction_hash AND user_id = :user_id";
    $paymentStmt = $pdo->prepare($paymentQuery);
    $paymentStmt->execute([
        'transaction_hash' => $transactionHash,
        'user_id' => $userId,
    ]);
    $payment = $paymentStmt->fetch();

    if (!$payment) {
        throw new Exception("Payment record not found for this user.");
    }

    $expectedAmount = bcmul($payment['price'], bcpow('10', '18')); // Convert to Wei
    if ($transaction['value'] !== '0x' . dechex($expectedAmount)) {
        throw new Exception("Transaction amount does not match the expected amount.");
    }

    // Verify the transaction is not backdated
    $currentTimestamp = time();
    $transactionTimestamp = hexdec($transaction['blockNumber']); // Get block timestamp
    if ($transactionTimestamp > $currentTimestamp) {
        throw new Exception("Transaction timestamp is invalid.");
    }

    // Update payment status to 'completed' for the current user
    $updateQuery = "UPDATE payments SET payment_status = 'completed' WHERE transaction_hash = :transaction_hash AND user_id = :user_id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->execute([
        'transaction_hash' => $transactionHash,
        'user_id' => $userId,
    ]);

    // Fetch payment details for the current user
    $paymentQuery = "SELECT package_id FROM payments WHERE transaction_hash = :transaction_hash AND user_id = :user_id";
    $paymentStmt = $pdo->prepare($paymentQuery);
    $paymentStmt->execute([
        'transaction_hash' => $transactionHash,
        'user_id' => $userId,
    ]);
    $payment = $paymentStmt->fetch();

    if (!$payment) {
        throw new Exception("Payment record not found for this user.");
    }

    $packageId = $payment['package_id'];

    // Call addSubscription.php to update the database for the current user
    $subscriptionResponse = file_get_contents(
        "https://warrencol.com/assets/php/addSubscription.php",
        false,
        stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => json_encode([
                    'user_id' => $userId,
                    'package_id' => $packageId,
                    'transaction_hash' => $transactionHash,
                ]),
            ],
        ])
    );

    $subscriptionResult = json_decode($subscriptionResponse, true);

    if (!$subscriptionResult['success']) {
        throw new Exception($subscriptionResult['error'] ?? 'Failed to update subscription');
    }

    echo json_encode(['message' => 'Payment verified and subscription updated successfully!']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]); // Ensure the error is a string
}