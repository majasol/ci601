<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['sub']) || !isset($_SESSION['user']['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

