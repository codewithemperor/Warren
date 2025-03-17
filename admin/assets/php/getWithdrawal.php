<?php
header('Content-Type: application/json');
require '../../../assets/php/config.php'; // Ensure this path is correct

try {
    // Fetch all withdrawals with user details
    $stmt = $pdo->query("
        SELECT 
            w.id,
            w.user_id,
            w.amount,
            w.fee,
            w.usdt_address,
            w.status,
            w.created_at,
            u.full_name AS username,
            u.email
        FROM withdrawals w
        JOIN users u ON w.user_id = u.id
    ");
    $withdrawals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($withdrawals)) {
        throw new Exception("No withdrawals found", 404);
    }

    // Format the response
    $response = [
        'success' => true,
        'withdrawals' => $withdrawals,
    ];

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}