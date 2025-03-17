<?php
session_start();

// Check if the session is active and contains the required data
if (!isset($_SESSION['admin']) || !is_array($_SESSION['admin'])) {
    // Session is not active, redirect to login
    header('Location: http://localhost/warren/admin/index.php');
    exit();
}

$requiredKeys = ['id', 'email', 'full_name',];
$sessionData = $_SESSION['admin'];

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
    header('Location: http://localhost/warren/admin/index.php');
    exit();
}

// If the user is not subscribed, redirect to deposit