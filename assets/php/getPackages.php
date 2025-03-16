<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require './config.php'; // Ensure this path is correct

try {


    // Fetch all packages from the database
    $stmt = $pdo->query("SELECT * FROM packages");
    $packages = $stmt->fetchAll();

    // Return the packages as JSON
    echo json_encode($packages);
} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
