<?php
require_once __DIR__ . '/db.php';

require_once 'session.php';

if (!isset($_SESSION['user']['id'])) {
    die("User not authenticated");
}

$user_id = $_SESSION['user']['id'];

// Define function
function getItemsByCategory($user_id, $category) {
    $conn = getDbConnection();

    $stmt = $conn->prepare("SELECT id, name, category, color, image_data FROM clothes WHERE user_id = ? AND category = ?");
    $stmt->bind_param("ss", $user_id, $category);
    $stmt->execute();

    $result = $stmt->get_result();
    $items = [];

    while ($row = $result->fetch_assoc()) {
        $imageData = $row['image_data'];
        $imageUrl = $imageData ? 'data:image/jpeg;base64,' . base64_encode($imageData) : '';

        $items[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'category' => $row['category'],
            'color' => $row['color'],
            'image_url' => $imageUrl
        ];
    }

    $stmt->close();
    $conn->close();

    return $items;
}

