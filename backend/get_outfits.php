<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);


$conn = getDbConnection();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'error' => 'User not authenticated']);
    exit;
}

$userId = $_SESSION['user']['id'];

$sql = "
    SELECT 
        o.id as outfit_id,
        o.name,
        o.created_at,
        i_top.image_data AS top_image,
        i_bottom.image_data AS bottom_image,
        i_shoes.image_data AS shoes_image
    FROM outfits o
    JOIN outfit_items oi_top ON oi_top.outfit_id = o.id
    JOIN clothes i_top ON i_top.id = oi_top.clothing_id AND i_top.category = 'top'
    JOIN outfit_items oi_bottom ON oi_bottom.outfit_id = o.id
    JOIN clothes i_bottom ON i_bottom.id = oi_bottom.clothing_id AND i_bottom.category = 'bottom'
    JOIN outfit_items oi_shoes ON oi_shoes.outfit_id = o.id
    JOIN clothes i_shoes ON i_shoes.id = oi_shoes.clothing_id AND i_shoes.category = 'shoes'
    WHERE o.user_id = ?
";


$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

$outfits = [];
while ($row = $result->fetch_assoc()) {
    $id = $row['outfit_id'];
    if (!isset($outfits[$id])) {
        $outfits[$id] = [
            'name' => $row['name'],
            'created_at' => $row['created_at'],
            'top_url' => null,
            'bottom_url' => null,
            'shoes_url' => null,
        ];
    }

    $outfits[$id]['top_url'] = $row['top_image'] ? 'data:image/jpeg;base64,' . base64_encode($row['top_image']) : null;
    $outfits[$id]['bottom_url'] = $row['bottom_image'] ? 'data:image/jpeg;base64,' . base64_encode($row['bottom_image']) : null;
    $outfits[$id]['shoes_url'] = $row['shoes_image'] ? 'data:image/jpeg;base64,' . base64_encode($row['shoes_image']) : null;
}

$stmt->close();
$conn->close();

echo json_encode([
    "success" => true,
    "outfits" => array_values($outfits)
]);

