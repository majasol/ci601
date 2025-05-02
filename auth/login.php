<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/../vendor/autoload.php');

use Auth0\SDK\Auth0;
use Dotenv\Dotenv;

session_start();

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

try {
    $auth0 = new Auth0([
        'domain'        => $_ENV['AUTH0_DOMAIN'],
        'clientId'      => $_ENV['AUTH0_CLIENT_ID'],
        'clientSecret'  => $_ENV['AUTH0_CLIENT_SECRET'],
        'redirectUri'   => $_ENV['AUTH0_BASE_URL'] . '/auth/callback.php',
        'cookieSecret'  => $_ENV['AUTH0_COOKIE_SECRET'],
    ]);

    $url = $auth0->login();
    header('Location: ' . $url);
    exit;
} catch (Exception $e) {
    echo 'Caught Exception: ',  $e->getMessage(), "\n";
}
?>
