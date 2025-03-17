<?php
header('Content-Type: application/json');
require '../../../assets/php/config.php'; // Ensure this path is correct

try {
    // Fetch all users from the database
    $stmt = $pdo->query("
        SELECT 
            u.id, 
            u.full_name, 
            u.email, 
            u.password_hash, 
            u.withdrawal_password_hash, 
            u.referral_code, 
            u.referred_by, 
            u.created_at, 
            u.is_subscribed,
            r.full_name AS referrer_name
        FROM users u
        LEFT JOIN users r ON u.referred_by = r.id
    ");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($users)) {
        throw new Exception("No users found", 404);
    }

    // Format the response
    $formattedUsers = array_map(function ($user) {
        return [
            'id' => $user['id'],
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'referral_code' => $user['referral_code'],
            'referred_by' => $user['referred_by'] ? "{$user['referrer_name']} ({$user['referred_by']})" : "N/A", // Format: name(id)
            'created_at' => $user['created_at'],
            'is_subscribed' => $user['is_subscribed'],
        ];
    }, $users);

    $response = [
        'success' => true,
        'users' => $formattedUsers,
    ];

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}