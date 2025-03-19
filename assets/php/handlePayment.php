<?php
// handlePayment.php

// Include the database configuration file
require 'config.php'; // Ensure this path is correct

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"), true);

// Extract data
$userId = $_SESSION['user']['id']; // Get the user ID from the session
$package_id = $data['package_id'];
$price = $data['price'];

try {
    // Check if a payment record already exists for the user (regardless of package)
    $query = "SELECT id FROM payments WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $user_id]);
    $existingPayment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingPayment) {
        // Update the existing payment record
        $updateQuery = "UPDATE payments SET package_id = :package_id, price = :price, payment_status = 'pending', updated_at = NOW() WHERE id = :id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([
            'package_id' => $package_id,
            'price' => $price,
            'id' => $existingPayment['id']
        ]);

        echo json_encode(['success' => true, 'message' => 'Payment record updated successfully.']);
    } else {
        // Insert a new payment record
        $insertQuery = "INSERT INTO payments (user_id, package_id, price, payment_status, created_at) VALUES (:user_id, :package_id, :price, 'pending', NOW())";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->execute([
            'user_id' => $user_id,
            'package_id' => $package_id,
            'price' => $price
        ]);

        echo json_encode(['success' => true, 'message' => 'New payment record inserted successfully.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
