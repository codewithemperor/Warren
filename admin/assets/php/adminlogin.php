<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

    // Fetch admin from the database
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    if (!$stmt) {
        throw new Exception("Database error: Unable to prepare statement", 500);
    }
    $stmt->execute([$data['email']]);
    $admin = $stmt->fetch();

    if (!$admin) {
        throw new Exception('Email not registered', 400);
    }

    // Verify password
    if (!password_verify($data['password'], $admin['password_hash'])) {
        throw new Exception('Incorrect password', 400);
    }

    // Start a session and store admin data
    session_start();
    $_SESSION['admin'] = [
        'id' => $admin['id'],
        'email' => $admin['email'],
        'full_name' => $admin['full_name'],
    ];

    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'admin' => [
            'id' => $admin['id'],
            'email' => $admin['email'],
            'full_name' => $admin['full_name'],
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