<?php
// getAdminWallet.php

// Include the database configuration file
require 'config.php'; // Ensure this path is correct

try {
    // Fetch admin wallet address
    $query = "SELECT wallet_address FROM admin_wallet LIMIT 1";
    $stmt = $pdo->query($query);
    $wallet = $stmt->fetch();

    if ($wallet) {
        echo json_encode(['wallet_address' => $wallet['wallet_address']]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Admin wallet address not found']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}