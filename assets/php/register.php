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
    $requiredFields = ['full_name', 'email', 'password', 'confirm_password', 'withdrawal_password'];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            throw new Exception("$field is required", 400);
        }
    }

    // Validate email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address', 400);
    }

    // Validate password match
    if ($data['password'] !== $data['confirm_password']) {
        throw new Exception('Passwords do not match', 400);
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    if (!$stmt) {
        throw new Exception("Database error: Unable to prepare statement", 500);
    }
    $stmt->execute([$data['email']]);
    if ($stmt->rowCount() > 0) {
        throw new Exception('Email already registered', 400);
    }

    // Generate referral code
    $referralCode = generateReferralCode();

    // Handle referral code
    $referredBy = null;
    if (!empty($data['referral_code'])) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE referral_code = ?");
        $stmt->execute([$data['referral_code']]);
        $referredBy = $stmt->fetchColumn();
        if (!$referredBy) {
            throw new Exception('Invalid referral code', 400);
        }
    }

    // Hash passwords
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
    $withdrawalPasswordHash = password_hash($data['withdrawal_password'], PASSWORD_DEFAULT);

    // Start database transaction
    $pdo->beginTransaction();

    try {
        // Insert user
        $stmt = $pdo->prepare("INSERT INTO users 
            (full_name, email, password_hash, withdrawal_password_hash, referral_code, referred_by)
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['full_name'],
            $data['email'],
            $passwordHash,
            $withdrawalPasswordHash,
            $referralCode,
            $referredBy
        ]);

        // Get the newly created user ID
        $userId = $pdo->lastInsertId();

        // Start a session and store user data
        session_start();
        $_SESSION['user'] = [
            'id' => $userId,
            'email' => $data['email'],
            'full_name' => $data['full_name'],
            'referral_code' => $referralCode,
            'is_subscribed' => false // Default value
        ];

        // If referred, create referral relationship
        if ($referredBy) {
            $stmt = $pdo->prepare("INSERT INTO referrals (referrer_id, referred_id) VALUES (?, ?)");
            $stmt->execute([$referredBy, $userId]);
        }

        // Commit transaction
        $pdo->commit();

        // Success response
        echo json_encode([
            'success' => true,
            'message' => 'Account created successfully',
            'referral_code' => $referralCode
        ]);
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        throw $e; // Re-throw the exception
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

/**
 * Generate a unique referral code
 */
function generateReferralCode($length = 8) {
    return strtoupper(substr(md5(uniqid(rand(), true)), 0, $length));
}
