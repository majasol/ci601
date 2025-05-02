<?php
ob_start(); // Start output buffering
require_once 'session.php';
require_once 'db.php';

$conn = getDbConnection();

$user_id = $_SESSION['user']['id'];
$top_id = $_POST['top_id'] ?? null;
$bottom_id = $_POST['bottom_id'] ?? null;
$shoes_id = $_POST['shoes_id'] ?? null;

// Basic validation
if (!$top_id || !$bottom_id || !$shoes_id) {
    die("Missing clothing item(s).");
}

// Generate outfit name
$outfit_name = "My Outfit"; // You can customize this dynamically if you like

// Step 1: Insert into outfits
$stmt = $conn->prepare("INSERT INTO outfits (user_id, name, created_at) VALUES (?, ?, NOW())");
if (!$stmt) {
    die("Prepare failed (insert outfits): " . $conn->error);
}
$stmt->bind_param("ss", $user_id, $outfit_name);
if (!$stmt->execute()) {
    die("Execute failed (insert outfits): " . $stmt->error);
}

$outfit_id = $stmt->insert_id;

if (!$outfit_id) {
    die("Invalid outfit_id generated.");
}

// Step 2: Link clothes to outfit
$item_ids = [$top_id, $bottom_id, $shoes_id];
foreach ($item_ids as $item_id) {
    // echo "Linking item ID: $item_id to outfit ID: $outfit_id<br>"; // Remove or comment out
    $stmt = $conn->prepare("INSERT INTO outfit_items (outfit_id, clothing_id) VALUES (?, ?)");
    if (!$stmt) {
        die("Prepare failed (insert outfit_items): " . $conn->error);
    }
    $stmt->bind_param("ss", $outfit_id, $item_id); // use "ss" if outfit_id or clothing_id are UUIDs (strings)
    if (!$stmt->execute()) {
        die("Execute failed (insert outfit_items): " . $stmt->error);
    }
}

ob_end_clean(); // Clear the output buffer
header("Location: ../views/home.php");
exit;
