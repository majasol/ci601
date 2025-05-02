<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/../vendor/autoload.php');

use Auth0\SDK\Auth0;
use Dotenv\Dotenv;

session_start();

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Initialize Auth0 SDK
$auth0 = new Auth0([
    'domain'        => $_ENV['AUTH0_DOMAIN'],
    'clientId'      => $_ENV['AUTH0_CLIENT_ID'],
    'clientSecret'  => $_ENV['AUTH0_CLIENT_SECRET'],
    'redirectUri'   => $_ENV['AUTH0_BASE_URL'] . '/views/home.php', // Redirect *after* logout
    'cookieSecret'  => $_ENV['AUTH0_COOKIE_SECRET'],
]);

// Clear Auth0 session
$auth0->clear();

// Clear PHP session
$_SESSION = [];
session_destroy();

// Redirect to home page after logout
header('Location: /ci601/ci601/views/home.php');
exit;
?>
