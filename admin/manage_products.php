<?php
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login_page/Login_Page.php");
    exit();
}

// Handle Product Insert
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = $_POST['image']; // image filename

    $stmt = $conn->prepare("INSERT INTO products (name, price, stock, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdis", $name, $price, $stock, $image);
    $stmt->execute();
    header("Location: manage_products.php");
    exit();
}

// Fetch Products
$products = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - Admin</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 30px;
        }

        .product-card {
            width: 220px;
            background-color: #fff9f1;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
        }

        .product-card:hover {
            transform: scale(1.03);
        }

        .product-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .product-details {
            padding: 15px;
        }

        .product-details h3 {
            font-size: 18px;
            margin: 5px 0;
            color: #6d4c41;
        }

        .product-details p {
            margin: 4px 0;
            color: #444;
            font-size: 14px;
        }

        .stock-zero {
            color: red;
            font-weight: bold;
        }

        .product-actions {
            display: flex;
            justify-content: space-between;
            padding: 10px 15px 15px;
        }

        .product-actions a {
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 13px;
            text-decoration: none;
            color: white;
        }

        .edit-btn {
            background-color: #f4a261;
        }

        .delete-btn {
            background-color: #e76f51;
        }

        .add-product-form {
            background-color: #ffedd5;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
            max-width: 500px;
        }

        .add-product-form h3 {
            margin-bottom: 15px;
            color: #7b3f00;
        }

        .add-product-form input {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
        }

        .add-product-form button {
            background-color: #7b3f00;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .main {
            margin-left: 240px;
            padding: 30px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>🍰 Bakery Admin</h2>
    <ul>
            <li><a href="admin_dashboard.php">📊 Dashboard</a></li>
            <li><a href="manage_products.php">🧁 Manage Products</a></li>
            <li><a href="orders.php">🛒 Orders</a></li>
            <li><a href="report.php">📈 Reports</a></li>
            <li><a href="customer.php">👥 Customers</a></li>
            <li><a href="admin_message.php">📩 Messages</a></li>
            <li><a href="../logout.php">🚪 Logout</a></li>
    </ul>
</div>

<div class="main">
    <div class="header">
        <h1>Manage Products 🧁</h1>
        <p>View, add, edit, or delete your bakery items.</p>
    </div>

    <!-- Add Product Form -->
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="name" required placeholder="Product Name">
    <input type="number" name="price" required placeholder="Price">
    <input type="number" name="stock" required placeholder="Stock">
    
    <label>Upload Product Image</label>
    <input type="file" name="image" accept="image/*" required>

    <button type="submit" name="submit">Add Product</button>
</form>


    <!-- Product Cards -->
    <div class="product-container">
        <?php while ($row = mysqli_fetch_assoc($products)) { ?>
            <div class="product-card">
                <img src="../assets/images/<?= htmlspecialchars($row['image']) ?>" class="product-image" alt="<?= htmlspecialchars($row['name']) ?>">
                <div class="product-details">
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p>Price: ₹<?= number_format($row['price'], 2) ?></p>
                    <p>Stock: 
                        <span class="<?= $row['stock'] == 0 ? 'stock-zero' : '' ?>">
                            <?= $row['stock'] ?>
                        </span>
                    </p>
                </div>
            <div class="product-actions">
                <a href="edit_product.php?id=<?= $row['id']; ?>" class="edit-btn">Edit</a>
                <a href="delete_product.php?id=<?= $row['id']; ?>"  class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </div>

            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>
