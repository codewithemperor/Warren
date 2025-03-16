<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require './config.php'; // Ensure this path is correct

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
    $requiredFields = ['email', 'password'];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            throw new Exception("$field is required", 400);
        }
    }

    // Validate email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address', 400);
    }

    // Fetch user from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    if (!$stmt) {
        throw new Exception("Database error: Unable to prepare statement", 500);
    }
    $stmt->execute([$data['email']]);
    $user = $stmt->fetch();

    if (!$user) {
        throw new Exception('Email not registered', 400);
    }

    // Verify password
    if (!password_verify($data['password'], $user['password_hash'])) {
        throw new Exception('Incorrect password', 400);
    }

    // Start a session and store user data
    session_start();
    $_SESSION['user'] = [
        'id' => $user['id'],
        'email' => $user['email'],
        'full_name' => $user['full_name'],
        'referral_code' => $user['referral_code'],
        'is_subscribed' => $user['is_subscribed']
    ];

    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'user' => [
            'id' => $user['id'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'referral_code' => $user['referral_code'],
            'is_subscribed' => $user['is_subscribed']
        ]
    ]);
} catch (Exception $e) {
    // Error response
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'field' => $e->getCode() === 400 ? $e->getMessage() : null
    ]);
}
