<?php
// newWithdrawal.php

require 'vendor/autoload.php'; // Ensure Web3.php is installed via Composer
require 'config.php'; // Database configuration
use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Contract;
use Web3\Utils;
use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->load();

$infura_url = 'https://bsc-mainnet.infura.io/v3/' . $_ENV['INFURA_PROJECT_ID'];
$web3 = new Web3(new HttpProvider($infura_url));

// Load sensitive data from .env file
$private_key = $_ENV['PRIVATE_KEY'];
$wallet_address = $_ENV['WALLET_ADDRESS'];

// USDT BEP-20 Contract Address on BSC
$usdt_contract_address = '0x55d398326f99059fF775485246999027B3197955';

// Read input data
$data = json_decode(file_get_contents("php://input"), true);
$amount = $data['amount'];
$usdt_address = $data['usdt_address'];
$withdrawal_password = $data['withdrawal_password'];

// Validate withdrawal password
try {
    // Fetch the user's withdrawal password from the database
    $stmt = $pdo->prepare("SELECT withdrawal_password FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => 1]); // Replace with actual user ID (e.g., from session)
    $user = $stmt->fetch();

    if (!$user || $user['withdrawal_password'] !== $withdrawal_password) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid withdrawal password']);
        exit;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
    exit;
}

// Convert amount to Wei (USDT has 18 decimals)
$amount_in_wei = bcmul($amount, bcpow('10', '18'));

// Prepare transaction data
$contract = new Contract($web3->provider, file_get_contents('usdt_abi.json'));
$transferData = $contract->at($usdt_contract_address)->getData('transfer', $usdt_address, (int) $amount_in_wei);

$tx = [
    'from' => $wallet_address,
    'to' => $usdt_contract_address,
    'gas' => '0x7a120', // 500,000 gas limit
    'gasPrice' => Utils::toWei('5', 'gwei'), // 5 Gwei gas price
    'data' => $transferData
];

// Sign and send transaction
try {
    $web3->eth->sendTransaction($tx, function ($err, $tx_hash) use ($pdo, $amount, $usdt_address) {
        if ($err !== null) {
            http_response_code(500);
            echo json_encode(['message' => 'Withdrawal failed: ' . $err->getMessage()]);
            exit;
        }
        
        // Save to database
        $stmt = $pdo->prepare("INSERT INTO withdrawals (user_id, amount, usdt_address, transaction_hash, status) VALUES (:user_id, :amount, :usdt_address, :transaction_hash, 'pending')");
        $stmt->execute([
            'user_id' => 1, // Replace with actual user ID
            'amount' => $amount,
            'usdt_address' => $usdt_address,
            'transaction_hash' => $tx_hash,
        ]);

        echo json_encode(['message' => 'Withdrawal successful!', 'transaction_hash' => $tx_hash]);
    });
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Withdrawal failed: ' . $e->getMessage()]);
}