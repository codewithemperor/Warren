<?php
header('Content-Type: application/json');
require '../../../assets/php/config.php'; // Ensure this path is correct

try {
    // Get JSON input
    $input = file_get_contents('php://input');
    if (empty($input)) {
        throw new Exception("No input data received", 400);
    }

    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON data", 400);
    }

    // Validate required fields
    $requiredFields = ['id', 'status'];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            throw new Exception("$field is required", 400);
        }
    }

    // Update the withdrawal status
    $stmt = $pdo->prepare("UPDATE withdrawals SET status = ? WHERE id = ?");
    $stmt->execute([$data['status'], $data['id']]);

    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Withdrawal status updated successfully',
    ]);
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}