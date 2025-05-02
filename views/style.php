<?php
session_start();
require_once '../backend/get_items_by_category.php';

$conn = getDbConnection();
$user_id = $_SESSION['user']['id'];
$tops = getItemsByCategory($user_id, 'top');
$bottoms = getItemsByCategory($user_id, 'bottom');
$shoes = getItemsByCategory($user_id, 'shoes');

// Random selection
$randomTop = !empty($tops) ? $tops[array_rand($tops)] : null;
$randomBottom = !empty($bottoms) ? $bottoms[array_rand($bottoms)] : null;
$randomShoes = !empty($shoes) ? $shoes[array_rand($shoes)] : null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Outfit</title>
    <link rel="stylesheet" href="../style/main.css">
</head>
<body>
    <h1>Create an Outfit</h1>

    <form action="../backend/add_outfit.php" method="POST" class="outfit-form">
        <?php foreach (['top' => $randomTop, 'bottom' => $randomBottom, 'shoes' => $randomShoes] as $type => $item): ?>
            <div class="clothing-section">
                <h2><?= ucfirst($type) ?></h2>
                <?php if ($item): ?>
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= $type ?>" width="150">
                    <input type="hidden" name="<?= $type ?>_id" value="<?= $item['id'] ?>">
                    <button type="submit" formaction="style.php?change=<?= $type ?>">Change <?= ucfirst($type) ?></button>
                <?php else: ?>
                    <p>No <?= $type ?> available</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <div class="form-actions">
            <button type="submit">Add Outfit</button>
            <a href="home.php" class="back-button">← Back to Home</a>
        </div>
    </form>
</body>

</html>
