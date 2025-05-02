<?php
require_once '../backend/session.php'; // adjust path if needed
header('Content-Type: application/json');

// Include db.php to ensure getDbConnection() is available
require_once __DIR__ . '/db.php';

// Ensure the user is authenticated by checking for the 'sub' field in the session
if (!isset($_SESSION['user']['sub'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

// Step 1: Verify if user exists using the 'auth0_id'
$userId = $_SESSION['user']['sub'];  // 'sub' is the Auth0 user identifier

$conn = getDbConnection();

// Query the 'users' table to get the actual 'id' for the user
$query = "SELECT id FROM users WHERE auth0_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    // User doesn't exist in the database
    echo json_encode(['success' => false, 'message' => 'User does not exist in the database']);
    exit;
}

// Fetch the user_id (the actual 'id' from the 'users' table)
$stmt->bind_result($actualUserId);
$stmt->fetch();
$stmt->close();

// Step 2: Insert the new item into the 'clothes' table using the actual 'id' from 'users'
$name = $_POST['name'] ?? null;
$category = $_POST['category'] ?? '';
$subCategory = $_POST['sub_category'] ?? '';
$color = $_POST['color'] ?? '';
$timesUsed = $_POST['times_used'] ?? 0;
$cost = $_POST['cost'] ?? null;

if (!$name) {
    echo json_encode(['success' => false, 'message' => 'Name is required']);
    exit;
}

$imageData = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $filePath = $_FILES['image']['tmp_name'];
    if (file_exists($filePath)) {
        $imageData = file_get_contents($filePath);
    } else {
        echo json_encode(['success' => false, 'message' => 'Uploaded file not found']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No image uploaded or upload error']);
    exit;
}

// Insert the item with the correct user_id
$sql = "INSERT INTO clothes (user_id, name, category, sub_category, color, times_used, cost, image_data) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("sssssdss", $actualUserId, $name, $category, $subCategory, $color, $timesUsed, $cost, $imageData);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Item added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error executing statement: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error preparing statement: ' . $conn->error]);
}

$conn->close();
