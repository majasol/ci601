<?php
session_start();
$client_id = "Em8HvlnX0e1YFSrE9dW3vp7cgymtmOtT";
$redirect_uri = "http://localhost/auth0-callback.php";
$domain = "dev-meaqbljgqatv3dvg.uk.auth0.com";

$auth_url = "https://$domain/authorize?" . http_build_query([
    'response_type' => 'code',
    'client_id' => $client_id,
    'redirect_uri' => $redirect_uri,
    'scope' => 'openid profile email',
]);

header("Location: $auth_url");
exit;
?>