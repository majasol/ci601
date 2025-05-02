<?php
require_once __DIR__ . '/vendor/autoload.php'; // Load Composer dependencies

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Return the config as an associative array
return [
    'DB_HOST' => $_ENV['DB_HOST'],
    'DB_USER' => $_ENV['DB_USER'],
    'DB_PASS' => $_ENV['DB_PASS'],
    'DB_NAME' => $_ENV['DB_NAME'],
    'AUTH0_CLIENT_ID' => $_ENV['AUTH0_CLIENT_ID'],
    'AUTH0_DOMAIN' => $_ENV['AUTH0_DOMAIN'],
    'AUTH0_CLIENT_SECRET' => $_ENV['AUTH0_CLIENT_SECRET'],
    'AUTH0_COOKIE_SECRET' => $_ENV['AUTH0_COOKIE_SECRET'],
    'AUTH0_BASE_URL' => $_ENV['AUTH0_BASE_URL']
];
