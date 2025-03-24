<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once 'vendor/autoload.php'; // Load Composer dependencies
require_once 'config.php';  // Make sure this is correctly set to load your environment

use Auth0\SDK\Auth0;

$auth0 = new Auth0([
    'domain' => $_ENV['AUTH0_DOMAIN'],
    'client_id' => $_ENV['AUTH0_CLIENT_ID'],
    'client_secret' => $_ENV['AUTH0_CLIENT_SECRET'],
    'redirect_uri' => $_ENV['AUTH0_BASE_URL'] . '/auth0-callback.php',
    'scope' => 'openid profile email',
]);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get credentials (Check if user is logged in)
$session = $auth0->getCredentials();

if ($session === null) {
    // User isn't logged in, show the login prompt
    echo '<p>Please <a href="' . $auth0->getLoginUrl() . '">log in</a>.</p>';
    return;
}

// The user is logged in, show user information
echo '<pre>';
print_r($session->user);  // Display the user info
echo '</pre>';

echo '<p>You can now <a href="' . $auth0->getLogoutUrl() . '">log out</a>.</p>';
?>
