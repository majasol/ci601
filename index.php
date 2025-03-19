<?php
// Start session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is authenticated (session should contain the ID token)
if (!isset($_SESSION['id_token'])) {
    // If not authenticated, redirect to login page
    header("Location: login.php");
    exit;
}

// JWT token handling for user info
$jwt = $_SESSION['id_token'];
$parts = explode(".", $jwt);
$payload = json_decode(base64_decode($parts[1]), true);

// Set default page or get from query string
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Dynamically include the requested page
switch ($page) {
    case 'calendar':
        require 'views/calendar.php';
        break;
    case 'style':
        require 'views/style.php';
        break;
    default:
        require 'views/index.php'; // Home page
}

// Display user info after the page content
echo "Hello, " . htmlspecialchars($payload['name']) . "!<br>";
echo "Email: " . htmlspecialchars($payload['email']) . "<br>";
echo '<a href="logout.php">Logout</a>';
?>
