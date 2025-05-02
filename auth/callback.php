<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../backend/db.php'); // Make sure this path is correct

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

    $auth0->exchange(); // Process Auth0 login callback

    $credentials = $auth0->getCredentials();

    if ($credentials && $credentials->user) {
        // Convert user object to array
        $user = is_array($credentials->user) ? $credentials->user : json_decode(json_encode($credentials->user), true);
        $auth0_id = $user['sub'];

        // Lookup internal user ID
        $conn = getDbConnection();
        $stmt = $conn->prepare("SELECT id FROM users WHERE auth0_id = ?");
        $stmt->bind_param("s", $auth0_id);
        $stmt->execute();
        $stmt->bind_result($internal_id);
        $stmt->fetch();
        $stmt->close();
        $conn->close();

        if ($internal_id) {
            // Store full session
            $_SESSION['user'] = [
                'sub' => $auth0_id,
                'id' => $internal_id
            ];
            

            header('Location: /ci601/ci601/views/home.php');
            exit;
        } else {
            die("No internal user ID found for Auth0 ID: $auth0_id");
        }
    } else {
        echo "Login failed. No user credentials.";
    }
} catch (Exception $e) {
    echo 'Caught Exception: ', $e->getMessage();
}
