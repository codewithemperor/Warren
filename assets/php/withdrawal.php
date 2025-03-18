<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require './config.php'; // Ensure this path is correct
require './earnings_functions.php'; // Include reusable functions

try {
    // Start session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

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
    $requiredFields = ['amount', 'usdt_address', 'withdrawal_password'];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            throw new Exception("$field is required", 400);
        }
    }

    // Validate withdrawal amount
    if (!is_numeric($data['amount']) || $data['amount'] <= 0) {
        throw new Exception("Invalid withdrawal amount", 400);
    }

    // Validate USDT address
    if (strlen($data['usdt_address']) < 10) {
        throw new Exception("Invalid USDT address", 400);
    }

    // Get user ID from session
    if (!isset($_SESSION['user'])) {
        throw new Exception("Unauthorized", 401);
    }
    $userId = $_SESSION['user']['id'];

    // Fetch user's withdrawal password
    $stmt = $pdo->prepare("SELECT withdrawal_password_hash FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $user = $stmt->fetch();

    if (!$user) {
        throw new Exception("User not found", 404);
    }

    // Verify withdrawal password
    if (!password_verify($data['withdrawal_password'], $user['withdrawal_password_hash'])) {
        throw new Exception("Incorrect withdrawal password", 400);
    }

    // Set timezone to New York
    date_default_timezone_set('America/New_York');

    // Check withdrawal time (7 AM - 7 PM New York time)
    $currentHour = (int) date('H');
    if ($currentHour < 7 || $currentHour >= 19) {
        throw new Exception("Withdrawals are only allowed between 7 AM and 7 PM (New York time).", 400);
    }

    // Check withdrawal limit (twice per day)
    $withdrawalCountQuery = "
        SELECT COUNT(*)
        FROM withdrawals
        WHERE user_id = :user_id
        AND DATE(created_at) = CURDATE()
    ";
    $stmt = $pdo->prepare($withdrawalCountQuery);
    $stmt->execute(['user_id' => $userId]);
    $withdrawalCount = $stmt->fetchColumn();

    if ($withdrawalCount >= 2) {
        throw new Exception("You have reached the daily withdrawal limit (2 withdrawals per day)", 400);
    }

    // Calculate available balance
    $totalReferralEarnings = getTotalReferralEarnings($pdo, $userId);
    $totalTaskEarnings = getTotalTaskEarnings($pdo, $userId);
    $totalWithdrawals = getTotalWithdrawals($pdo, $userId);

    // Calculate total earnings and available balance
    $totalEarnings = $totalReferralEarnings + $totalTaskEarnings;
    $availableBalance = $totalEarnings - $totalWithdrawals;

    // Validate withdrawal amount
    $withdrawalAmount = (float) $data['amount'];
    if ($withdrawalAmount > $availableBalance) {
        throw new Exception("Insufficient balance. Available balance: $" . number_format($availableBalance, 2), 400);
    }

    // Calculate withdrawal fee (1.5%)
    $withdrawalFee = round($withdrawalAmount * 0.015, 2);

    // Start a database transaction
    $pdo->beginTransaction();

    try {
        // Insert withdrawal request
        $stmt = $pdo->prepare("
            INSERT INTO withdrawals (user_id, amount, fee, usdt_address, status)
            VALUES (:user_id, :amount, :fee, :usdt_address, 'pending')
        ");
        $stmt->execute([
            'user_id' => $userId,
            'amount' => $withdrawalAmount,
            'fee' => $withdrawalFee,
            'usdt_address' => $data['usdt_address']
        ]);

        // Commit the transaction
        $pdo->commit();

        // Success response
        echo json_encode([
            'success' => true,
            'message' => 'Withdrawal request submitted successfully',
            'data' => [
                'amount' => $withdrawalAmount,
                'fee' => $withdrawalFee,
                'net_amount' => $withdrawalAmount - $withdrawalFee,
                'usdt_address' => $data['usdt_address']
            ]
        ]);
    } catch (Exception $e) {
        // Rollback the transaction on error
        $pdo->rollBack();
        throw new Exception("Failed to process withdrawal: " . $e->getMessage(), 500);
    }

} catch (Exception $e) {
    // Error response
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'field' => $e->getCode() === 400 ? $e->getMessage() : null
    ]);
}