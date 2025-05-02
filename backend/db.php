<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getDbConnection() {
    // Hardcoded database credentials
$host = '165.227.235.122';         // Database host
$username = 'ms2360_potato2';  // Your database username
$password = 'pi;PP9=pFGuk';  // Your database password
$dbname = 'ms2360_wardrobe_manager';    // Your database name

    // Create and return a new database connection
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die('Database connection failed: ' . $conn->connect_error);
    }
    return $conn;
}
?>