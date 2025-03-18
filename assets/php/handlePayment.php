<?php
// handlePayment.php

// Include the database configuration file
require 'config.php'; // Ensure this path is correct

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"), true);

// Extract data
$user_id = $data['user_id'];
$package_id = $data['package_id'];
$price = $data['price'];
$currency = $data['currency']; // New field for currency

try {
    // Insert a new payment record
    $query = "INSERT INTO payments (user_id, package_id, price,  payment_status) VALUES (:user_id, :package_id, :price,  'pending')";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'user_id' => $user_id,
        'package_id' => $package_id,
        'price' => $price,
       
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}