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
$hashedUserId = $data['order_id']; // Hashed user ID from the order_id

try {
    // Update the database if payment is confirmed
    if ($paymentStatus === 'finished') {
        // Fetch all users to verify the hashed user ID
        $query = "SELECT id FROM users";
        $stmt = $pdo->query($query);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $userId = null;
        foreach ($users as $user) {
            if (password_verify($user['id'], $hashedUserId)) {
                $userId = $user['id'];
                break;
            }
        }

        if (!$userId) {
            throw new Exception('User not found');
        }

        // Fetch the subscription details associated with this payment
        $query = "SELECT package_id FROM payments WHERE payment_id = :payment_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['payment_id' => $paymentId]);
        $payment = $stmt->fetch();

        if ($payment) {
            // Call addSubscription.php to update the database
            $subscriptionResponse = file_get_contents(
                "https://warrencoinv.com/assets/php/addSubscription.php",
                false,
                stream_context_create([
                    'http' => [
                        'method' => 'POST',
                        'header' => 'Content-Type: application/json',
                        'content' => json_encode([
                            'user_id' => $userId, // Pass the verified user ID
                            'package_id' => $payment['package_id'],
                            'transaction_hash' => $paymentId,
                        ]),
                    ],
                ])
            );

            $subscriptionResult = json_decode($subscriptionResponse, true);

            if (!$subscriptionResult['success']) {
                throw new Exception($subscriptionResult['error'] ?? 'Failed to update subscription');
            }
        } else {
            throw new Exception('Payment details not found');
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