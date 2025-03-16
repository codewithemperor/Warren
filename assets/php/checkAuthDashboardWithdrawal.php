<?php
session_start();

// Check if the session is active and contains the required data
if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
    // Session is not active, redirect to login
    header('Location: https://warrencoinv.com/login.php');
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
    header('Location: https://warrencoinv.com/login.php');
    exit();
}

// If the user is not subscribed, redirect to deposit
if (!$sessionData['is_subscribed']) {
    header('Location: https://warrencoinv.com/deposit.php');
    exit();
}

// If the session is valid and the user is subscribed, do nothing
// The dashboard/withdrawal page will continue to load
