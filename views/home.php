<?php
require_once '../backend/session.php'; // adjust path if needed
require_once '../backend/db.php'; // or wherever your DB connection is

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);


if (isset($_SESSION['user']) && isset($_SESSION['user']['sub']) && !isset($_SESSION['user']['id'])) {
    $auth0_id = $_SESSION['user']['sub'];

    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT id FROM users WHERE auth0_id = ?");
    $stmt->bind_param("s", $auth0_id);
    $stmt->execute();
    $stmt->bind_result($internal_id);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    if ($internal_id) {
        $_SESSION['user']['id'] = $internal_id;
    } else {
        die("No internal user ID found for Auth0 ID: $auth0_id");
    }
}


require_once '../backend/get_items.php';
$items = getItemsFromDatabase();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/main.css">

    <title>Wardrobe Manager</title>
</head>

<body>
    <div id="wrapper">
        <div class="header">
            <h1>WM</h1>

            <div class="profile-dropdown">
                <img src="../img/profile.png" alt="Profile" id="profile-img">
                <div class="dropdown-content" id="dropdown-menu">
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="../../auth/logout.php" id="auth-action">Logout</a>
                    <?php else: ?>
                        <a href="../../auth/login.php" id="auth-action">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="tab">
           <button class="tablinks" data-tab-target="Items">Items</button>
<button class="tablinks" data-tab-target="Outfits">Outfits</button>
<button class="tablinks" data-tab-target="Collections">Collections</button>

        </div>

        <div id="Items" class="tabcontent">
    <div class="items-container" id="items-container">
        <!-- JS will populate this -->
    </div>
</div>


        <div id="Outfits" class="tabcontent">
</div>

        <div id="Collections" class="tabcontent">
            <h3>Collections</h3>
            <p>Lorem ipsum dolor sit amet consectetur...</p>
        </div>
    </div> <!-- ✅ Properly close #wrapper here -->

    <footer>
        <a href="home.php">
            <img src="../img/wardrobe.png" alt="Wardrobe">
        </a>

        <div class="dropup">
            <button class="dropbtn">
                <img src="../img/plus.png" alt="plus">
            </button>
            <div class="dropup-content">
                <a href="#" id="add-item-btn">Add item</a>
                <a href="style.php">Create Outfit</a>
                <a href="#">Create Collection</a>
            </div>
        </div>

        <a href="calendar.php">
            <img src="../img/calendar.png" alt="calendar">
        </a>
    </footer>

    <!-- Upload Modal -->
    <div id="upload-form" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="item-form" action="../backend/add_item.php" method="POST" enctype="multipart/form-data">
                <label for="item-name">Name</label>
                <input type="text" id="item-name" name="name" required><br>

                <label for="item-image">Image</label>
                <input type="file" id="item-image" name="image" required><br>

                <label for="item-category">Category</label>
                <select id="item-category" name="category" required>
                    <option value="">--Select a Category--</option>
                    <option value="top">Top</option>
                    <option value="bottom">Bottom</option>
                    <option value="shoes">Shoes</option>
                    <option value="accessories">Accessories</option>
                    <option value="other">Other</option>
                </select>

                <label for="item-sub-category">Sub-category</label>
                <input type="text" id="item-sub-category" name="sub_category"><br>

                <label for="item-color">Color</label>
                <input type="text" id="item-color" name="color"><br>

                <label for="item-times-used">Times Used</label>
                <input type="number" id="item-times-used" name="times_used"><br>

                <label for="item-cost">Cost</label>
                <input type="text" id="item-cost" name="cost"><br>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <script src="../js/index.js"></script>
</body>


</html>