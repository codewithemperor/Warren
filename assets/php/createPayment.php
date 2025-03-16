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

$userId = $_SESSION['user']['id'];
$packageId = $data['package_id'];
$price = $data['price'];
$planTitle = $data['planTitle'];

// Hash the user ID for security
$hashedUserId = password_hash($userId, PASSWORD_DEFAULT);

$nowPaymentsApiKey = '24E3BED-VRJMVPS-G9BBX04-35M34RZ'; // Replace with your actual API key
$nowPaymentsUrl = 'https://api.nowpayments.io/v1/invoice';

$paymentData = [
    'price_amount' => $price,
    'price_currency' => 'usd',
    'order_id' => $hashedUserId, // Use the hashed user ID as the order ID
    'order_description' => $planTitle, // Include the plan title in the description
    'ipn_callback_url' => 'https://warrencoinv.com/assets/php/ipnCallback.php', 
    'success_url' => 'https://warrencoinv.com/dashboard.php', 
    'cancel_url' => 'https://warrencoinv.com/deposit.php',
];

try {
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
            'invoice_url' => $responseData['invoice_url'], // Redirect the user to this URL
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