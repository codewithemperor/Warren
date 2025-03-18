<?php
// saveTask.php

// Start the session (if not already started)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration file
require 'config.php'; // Ensure this path is correct

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get the logged-in user's ID from the session
$userId = $_SESSION['user']['id'];

// Validate the request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// Get the task ID from the request body
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['task_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Task ID is required']);
    exit;
}

$taskId = $data['task_id'];

try {
    // Enable PDO error reporting
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the user has an active subscription
    $query = "
        SELECT s.*, p.daily_withdrawal_limit, p.price 
        FROM subscriptions s
        JOIN packages p ON s.package_id = p.id
        WHERE s.user_id = :user_id 
        AND s.end_date > NOW()
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $userId]);
    $activePackage = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$activePackage) {
        http_response_code(400);
        echo json_encode(['error' => 'No active subscription found']);
        exit;
    }

    // Check if the user has already completed a task today
    $query = "
        SELECT COUNT(*) 
        FROM user_tasks 
        WHERE user_id = :user_id 
        AND DATE(completed_at) = CURDATE()
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $userId]);
    $taskCount = $stmt->fetchColumn();

    if ($taskCount > 0) {
        http_response_code(400);
        echo json_encode(['error' => 'You can only complete one task per day']);
        exit;
    }

    // Calculate the daily withdrawal amount
    $dailyWithdrawalAmount = ($activePackage['price'] * $activePackage['daily_withdrawal_limit']) / 100;

    // Insert the task into the user_tasks table
    $query = "
        INSERT INTO user_tasks (user_id, task_id, task_type, status, completed_at, daily_amount)
        VALUES (:user_id, :task_id, 'daily', 'completed', NOW(), :daily_amount)
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'user_id' => $userId,
        'task_id' => $taskId,
        'daily_amount' => $dailyWithdrawalAmount
    ]);

    // Return success response
    echo json_encode(['success' => true, 'daily_amount' => $dailyWithdrawalAmount]);

} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}