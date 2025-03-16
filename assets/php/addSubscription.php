<?php
// addSubscription.php

require 'config.php'; // Ensure this path is correct

// Get the request body
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['user_id']) || !isset($data['package_id']) || !isset($data['transaction_hash'])) {
    http_response_code(400);
    echo json_encode(['error' => 'User ID, Package ID, and Transaction Hash are required']);
    exit;
}

$userId = $data['user_id'];
$packageId = $data['package_id'];
$transactionHash = $data['transaction_hash'];

try {
    // Enable PDO error reporting
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Start a database transaction
    $pdo->beginTransaction();

    // Fetch package details
    $query = "SELECT price, validity_days FROM packages WHERE id = :package_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['package_id' => $packageId]);
    $package = $stmt->fetch();

    if (!$package) {
        http_response_code(404);
        echo json_encode(['error' => 'Package not found']);
        exit;
    }

    // Calculate start and end dates
    $startDate = date('Y-m-d H:i:s');
    $endDate = date('Y-m-d H:i:s', strtotime("+{$package['validity_days']} days"));

    // Insert subscription into the database
    $query = "
        INSERT INTO subscriptions (user_id, package_id, start_date, end_date, transaction_hash)
        VALUES (:user_id, :package_id, :start_date, :end_date, :transaction_hash)
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'user_id' => $userId,
        'package_id' => $packageId,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'transaction_hash' => $transactionHash,
    ]);

    // Update user's is_subscribed status
    $query = "UPDATE users SET is_subscribed = 1 WHERE id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $userId]);

    // Commit the transaction
    $pdo->commit();

    // Return success response
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    // Roll back the transaction on error
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    error_log('Database error: ' . $e->getMessage()); // Log the error
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
    error_log('Error: ' . $e->getMessage()); // Log the error
}