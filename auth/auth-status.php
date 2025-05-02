<?php
require_once '../backend/session.php'; // adjust path if needed

header('Content-Type: application/json');

// Check if the user is authenticated (adjust based on your Auth0 setup)
$isAuthenticated = isset($_SESSION['user']);

echo json_encode([
    "isAuthenticated" => $isAuthenticated
]);