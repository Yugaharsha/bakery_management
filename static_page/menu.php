<?php
include '../db.php';  // your database connection

// Fetch products from DB
$sql = "SELECT name, price, image FROM products ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Bakery Menu - Thilaga Bakery</title>
<style>
    /* Your existing CSS here */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background-color: #f8f3e7;
        color: #4a2c00;
    }

    header {
        width: 100%;
        background-color: #6b3e09;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 50px;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .logo {
        font-size: 26px;
        font-weight: bold;
    }

    nav a {
        text-decoration: none;
        color: white;
        margin-left: 20px;
        font-size: 16px;
        transition: 0.3s;
    }

    nav a:hover {
        color: #ffe1b3;
    }

    .page-title {
        text-align: center;
        font-size: 36px;
        font-weight: bold;
        margin: 120px 0 20px;
        color: #6b3e09;
    }

    .menu-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        width: 90%;
        max-width: 1200px;
        margin: auto;
        padding-bottom: 50px;
    }

    .menu-item {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        overflow: hidden;
        text-align: center;
        padding: 15px;
        transition: transform 0.3s ease;
    }

    .menu-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 10px;
    }

    .menu-item h3 {
        margin: 15px 0 5px;
        font-size: 20px;
        color: #4a2c00;
    }

    .menu-item p {
        font-size: 18px;
        color: #6b3e09;
        font-weight: bold;
    }

    .menu-item:hover {
        transform: translateY(-10px);
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 28px;
        }
    }
</style>
</head>
<body>

<header>
    <div class="logo">🍰 Thilaga Bakery</div>
    <nav>
        <a href="../index.html">Home</a>
        <a href="menu.php">Menu</a>
        <a href="../static_page/aboutpage.html">About Us</a>
        <a href="#">Contact</a>
        <a href="../login_page/Login_Page.php">Sign In</a>
    </nav>
</header>

<div class="page-title">Our Delicious Menu</div>

<div class="menu-container">
    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Make sure image path is correct relative to this file
            $imagePath = htmlspecialchars($row['image']);
            $name = htmlspecialchars($row['name']);
            $price = number_format($row['price'], 2); // format price with 2 decimals

            echo '<div class="menu-item">';
            echo '<img src="../assets/images/' . $imagePath . '" alt="' . $name . '">';
            echo '<h3>' . $name . '</h3>';
            echo '<p>₹' . $price . '</p>';
            echo '</div>';
        }
    } else {
        echo '<p style="text-align:center; color:#6b3e09;">No items found in the menu.</p>';
    }
    ?>
</div>

</body>
</html>
