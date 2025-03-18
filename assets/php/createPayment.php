<?php
// createPayment.php

require 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['package_id']) || !isset($data['price']) || !isset($data['planTitle'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Package ID, Price, and Plan Title are required']);
    exit;
}

$userId = $_SESSION['user']['id']; // Directly use the user ID
$packageId = $data['package_id'];
$price = $data['price'];
$planTitle = $data['planTitle'];

try {
    $query = "SELECT wallet_address FROM admin_wallet LIMIT 1"; // Assuming the API key is stored in the wallet_address column
    $stmt = $pdo->query($query);
    $wallet = $stmt->fetch();

    if (!$wallet) {
        throw new Exception('NowPayments API key not found in the database');
    }

    $nowPaymentsApiKey = $wallet['wallet_address']; 
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

try {
    // Insert payment details into the database
    $query = "INSERT INTO payments (user_id, package_id, price, payment_status, created_at) 
              VALUES (:user_id, :package_id, :price, 'pending', NOW())";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'user_id' => $userId,
        'package_id' => $packageId,
        'price' => $price,
    ]);

    // Get the last inserted payment ID
    $paymentId = $pdo->lastInsertId();

    // Create the NowPayments invoice
    $nowPaymentsUrl = 'https://api.nowpayments.io/v1/invoice';
    $paymentData = [
        'price_amount' => $price,
        'price_currency' => 'usd',
        'order_id' => $paymentId, // Use the payment ID as the order ID
        'order_description' => $planTitle,
        'ipn_callback_url' => 'https://warrencol.com/assets/php/ipnCallback.php',
        'success_url' => 'https://warrencol.com/dashboard.php',
        'cancel_url' => 'https://warrencol.com/deposit.php',
        'is_fee_paid_by_user' => true,
    ];

    $ch = curl_init($nowPaymentsUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'x-api-key: ' . $nowPaymentsApiKey,
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));
    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);

    // Log the API response for debugging
    error_log('NowPayments API Response: ' . print_r($responseData, true));

    if (isset($responseData['invoice_url'])) {
        echo json_encode([
            'success' => true,
            'invoice_url' => $responseData['invoice_url'],
        ]);
    } else {
        $errorMessage = $responseData['message'] ?? 'Failed to create invoice';
        error_log('NowPayments API Error: ' . $errorMessage);
        throw new Exception($errorMessage);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}