<?php
session_start();

// Check if the session is active and contains the required data
if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
    // Session is not active, redirect to login
    header('Location: http://localhost/warren/login.php');
    exit();
}

$requiredKeys = ['id', 'email', 'full_name', 'referral_code', 'is_subscribed'];
$sessionData = $_SESSION['user'];

// Verify that all required keys are present in the session data
$isSessionValid = true;
foreach ($requiredKeys as $key) {
    if (!array_key_exists($key, $sessionData)) {
        $isSessionValid = false;
        break;
    }
}

// If the session is not valid, redirect to login
if (!$isSessionValid) {
    header('Location: http://localhost/warren/login.php');
    exit();
}

// Check if the user is subscribed
if ($sessionData['is_subscribed']) {
    // User is subscribed, redirect to dashboard
    header('Location: http://localhost/warren/dashboard.php');
    exit();
}

// If the session is valid and the user is not subscribed, continue loading the deposit page
