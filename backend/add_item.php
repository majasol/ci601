<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $category = $_POST["category"];

    // Handle Image Upload
    if (isset($_FILES["image"])) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imageUrl = "/uploads/" . $fileName;
        } else {
            echo json_encode(["error" => "Image upload failed"]);
            exit();
        }
    }

    // Insert into database
    try {
        $stmt = $conn->prepare("INSERT INTO clothes (name, image_url, category) VALUES (?, ?, ?)");
        $stmt->execute([$name, $imageUrl, $category]);
        echo json_encode(["success" => "Item added"]);
    } catch (Exception $e) {
        echo json_encode(["error" => "Failed to add item"]);
    }
}
?>
