<?php
// ipnCallback.php

require 'config.php'; // Ensure this path is correct

// Log the incoming IPN data for debugging
$rawData = file_get_contents('php://input');
error_log('IPN Data: ' . $rawData);

// Decode the JSON data
$data = json_decode($rawData, true);

// Validate the IPN data
if (!isset($data['payment_id']) || !isset($data['payment_status']) || !isset($data['order_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid IPN data']);
    exit;
}

$paymentId = $data['payment_id'];
$paymentStatus = $data['payment_status'];
$orderId = $data['order_id']; // Use the order_id to retrieve user_id and package_id

try {
    // Fetch the payment details, including user_id and package_id
    $query = "SELECT user_id, package_id FROM payments WHERE id = :order_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['order_id' => $orderId]);
    $payment = $stmt->fetch();

    if (!$payment) {
        throw new Exception('Payment details not found');
    }

    $userId = $payment['user_id'];
    $packageId = $payment['package_id'];

    // Update the database if payment is confirmed
    if ($paymentStatus === 'finished') {
        // Update payment status to 'completed'
        $updateQuery = "UPDATE payments SET payment_status = 'completed' WHERE id = :order_id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute(['order_id' => $orderId]);

        // Call addSubscription.php to update the database
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
                        'transaction_hash' => $paymentId,
                    ]),
                ],
            ])
        );

        $subscriptionResult = json_decode($subscriptionResponse, true);

        if (!$subscriptionResult['success']) {
            throw new Exception($subscriptionResult['error'] ?? 'Failed to update subscription');
        }
    }

    // Return success response
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Log the error
    error_log('IPN Callback Error: ' . $e->getMessage());

    // Return error response
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}