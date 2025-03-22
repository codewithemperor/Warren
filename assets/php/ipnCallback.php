<?php
// ipnCallback.php

require 'config.php'; // Ensure this path is correct

class NowPaymentsIPN {
    private $ipn_secret;

    public function __construct($ipn_secret) {
        $this->ipn_secret = $ipn_secret;
    }

    public function check_ipn_request_is_valid() {
        $error_msg = "Unknown error";
        $auth_ok = false;
        $request_data = null;

        if (isset($_SERVER['HTTP_X_NOWPAYMENTS_SIG']) && !empty($_SERVER['HTTP_X_NOWPAYMENTS_SIG'])) {
            $received_hmac = $_SERVER['HTTP_X_NOWPAYMENTS_SIG'];
            $request_json = file_get_contents('php://input');
            $request_data = json_decode($request_json, true);

            if ($request_json !== false && !empty($request_json)) {
                ksort($request_data); // Sort the data by keys
                $sorted_request_json = json_encode($request_data, JSON_UNESCAPED_SLASHES);

                // Generate HMAC signature
                $hmac = hash_hmac("sha512", $sorted_request_json, trim($this->ipn_secret));

                // Compare the generated HMAC with the received HMAC
                if (hash_equals($hmac, $received_hmac)) {
                    $auth_ok = true;
                } else {
                    $error_msg = 'HMAC signature does not match';
                }
            } else {
                $error_msg = 'Error reading POST data';
            }
        } else {
            $error_msg = 'No HMAC signature sent.';
        }

        if (!$auth_ok) {
            error_log('IPN HMAC Validation Failed: ' . $error_msg);
            http_response_code(403); // Forbidden
            echo json_encode(['error' => $error_msg]);
            exit;
        }

        return $request_data;
    }
}

// Initialize the IPN handler with your IPN secret
$ipn_secret = 'XZkAcklZcDRf0mq66jwHhw+PP6RzfYSg'; // Replace with your actual IPN secret from NowPayments
$nowPaymentsIPN = new NowPaymentsIPN($ipn_secret);

// Validate the IPN request
$data = $nowPaymentsIPN->check_ipn_request_is_valid();

// Log the incoming IPN data for debugging
error_log('IPN Data: ' . print_r($data, true));

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
            "assets/php/addSubscription.php",
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

    // Redirect to dashboard.php after all tasks are completed
    header('Location: dashboard.php');
    exit;
} catch (Exception $e) {
    // Log the error
    error_log('IPN Callback Error: ' . $e->getMessage());

    // Return error response
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}