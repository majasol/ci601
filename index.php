<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'vendor/autoload.php'; // Load Composer dependencies

use Auth0\SDK\Auth0;
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Initialize Auth0 SDK
$auth0 = new Auth0([
    'domain'        => $_ENV['AUTH0_DOMAIN'],
    'clientId'      => $_ENV['AUTH0_CLIENT_ID'],
    'clientSecret'  => $_ENV['AUTH0_CLIENT_SECRET'],
    'redirectUri'   => $_ENV['AUTH0_BASE_URL'] . '/auth0-callback.php',
    'cookieSecret'  => $_ENV['AUTH0_COOKIE_SECRET'],
]);

// Handle login request after Auth0 is initialized
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $auth0->login();
    exit; // Stop script execution after redirect
}

// Handle Auth0 callback and set session data
if (isset($_GET['code']) && isset($_GET['state'])) {
    $auth0->exchangeCode($_GET['code'], $_GET['state']);
    header('Location: /views/home.php'); // Redirect to home.php after successful login
    exit;
}

// If user is logged in, set session data
$session = $auth0->getCredentials();
if ($session) {
    $_SESSION['user'] = $session->user;
    header('Location: /views/home.php'); // Redirect to home.php after successful login
    exit;
}
?>
