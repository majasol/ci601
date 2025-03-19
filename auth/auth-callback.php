<?php
session_start();

$client_id = "Em8HvlnX0e1YFSrE9dW3vp7cgymtmOtT";
$client_secret = "1CJU1GK_njRm3JpUV3RAYniZhytHgHe5KZjGUeyGuBRal4a4W1_u9M0_U6zlS7gI";
$redirect_uri = "http://localhost/auth0-callback.php";
$domain = "dev-meaqbljgqatv3dvg.uk.auth0.com";

if (!isset($_GET['code'])) {
    die("No authorization code received.");
}

$code = $_GET['code'];

// Exchange code for access token and ID token
$token_url = "https://$domain/oauth/token";
$data = [
    "grant_type" => "authorization_code",
    "client_id" => $client_id,
    "client_secret" => $client_secret,
    "code" => $code,
    "redirect_uri" => $redirect_uri
];

$options = [
    "http" => [
        "header" => "Content-Type: application/x-www-form-urlencoded\r\n",
        "method" => "POST",
        "content" => http_build_query($data)
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($token_url, false, $context);

if (!$response) {
    die("Error fetching the access token.");
}

$response_data = json_decode($response, true);

// Check if we got the access token and ID token
if (isset($response_data['access_token']) && isset($response_data['id_token'])) {
    $_SESSION['access_token'] = $response_data['access_token'];
    $_SESSION['id_token'] = $response_data['id_token'];
    
    // Make sure session is stored properly
    // Debugging session variables
    var_dump($_SESSION); 

    // Redirect to homepage after successful login
    header("Location: index.php");
    exit;
}

echo "Failed to authenticate.";
