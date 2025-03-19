<?php
session_start();

$secret = "1CJU1GK_njRm3JpUV3RAYniZhytHgHe5KZjGUeyGuBRal4a4W1_u9M0_U6zlS7gI";
$issuer = "https://dev-meaqbljgqatv3dvg.uk.auth0.com/";

// Check if token is present in authorization header
if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    http_response_code(401);
    echo json_encode(["error" => "No token provided"]);
    exit;
}

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
$token = str_replace("Bearer ", "", $authHeader);

// Decode JWT (without verifying)
$parts = explode(".", $token);

if (count($parts) !== 3) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid token format"]);
    exit;
}

// Decode the payload part of the JWT
$payload = json_decode(base64_decode($parts[1]), true);

// Validate the token manually
if ($payload['iss'] !== $issuer) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid issuer"]);
    exit;
}

echo json_encode(["message" => "Access granted", "user" => $payload]);
