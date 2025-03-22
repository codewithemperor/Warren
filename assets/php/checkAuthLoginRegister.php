<?php
session_start();

// Check if the session is active and contains the required data
if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
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

    // If the session is valid, redirect based on is_subscribed status
    if ($isSessionValid) {
        if ($sessionData['is_subscribed']) {
            header('Location: dashboard.php');
            exit();
        } else {
            header('Location: deposit.php');
            exit();
        }
    }
}

// If the session is not valid or data is missing, do nothing
// The login/register page will continue to load
