<?php
function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception('.env file not found');
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Skip comments
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

// Load .env
loadEnv(__DIR__ . '/../.env');

function getItemsFromDatabase() {
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


    if (!isset($_SESSION['user']['id'])) {
        die("User not authenticated");
    }

    $userId = $_SESSION['user']['id'];

    $dbHost = getenv('DB_HOST');
    $dbUser = getenv('DB_USER');
    $dbPass = getenv('DB_PASS');
    $dbName = getenv('DB_NAME');

    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, name, image_data FROM clothes WHERE user_id = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];

    while ($row = $result->fetch_assoc()) {
        $imageData = $row['image_data'];

        $imageUrl = $imageData !== null
            ? 'data:image/jpeg;base64,' . base64_encode($imageData)
            : ''; // or default image

        $items[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'image_url' => $imageUrl
        ];
    }

    $stmt->close();
    $conn->close();

    return $items;
}

if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'items' => getItemsFromDatabase()
    ]);
}

?>
