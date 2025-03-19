<?php
include 'db_config.php'; // Include your database connection

header('Content-Type: application/json');

try {
    $stmt = $conn->query("SELECT * FROM clothes");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($items);
} catch (PDOException $e) {
    echo json_encode(["error" => "Failed to fetch items"]);
}
?>
