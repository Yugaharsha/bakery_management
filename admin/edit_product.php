<?php
include '../db.php'; // Include database connection

// ==========================
// Handle Form Submission
// ==========================
if (isset($_POST['update'])) { // If update button is clicked
    $id = intval($_POST['id']); // Sanitize product ID
    $name = mysqli_real_escape_string($conn, $_POST['name']); // Sanitize product name
    $price = floatval($_POST['price']); // Convert price to float
    $stock = intval($_POST['stock']); // Convert stock to integer

    // Get current product image from DB
    $query = "SELECT image FROM products WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $image = $row['image'];

    // ==========================
    // Handle Image Upload
    // ==========================
    if (!empty($_FILES['image']['name'])) { // If new image is uploaded
        $target_dir = "../assets/images/"; // Folder to store images
        $new_image_name = time() . "_" . basename($_FILES["image"]["name"]); // Unique image name
        $target_file = $target_dir . $new_image_name;

        // Move uploaded file to target folder
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $new_image_name; // Update image variable with new name
        }
    }

    // ==========================
    // Update Product in Database
    // ==========================
    $update_sql = "UPDATE products SET 
                   name = '$name', 
                   price = $price, 
                   stock = $stock, 
                   image = '$image' 
                   WHERE id = $id";

    // If update successful, redirect to manage products page
    if (mysqli_query($conn, $update_sql)) {
        header("Location: manage_products.php?msg=updated");
        exit();
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}

// ==========================
// Fetch Product for Editing
// ==========================
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize ID from URL
    $query = "SELECT * FROM products WHERE id = $id"; // Fetch product data
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
} else {
    die("Product ID is missing."); // Error if ID not provided
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="admin_styles.css"> <!-- External CSS -->
    <style>
        /* ===== PAGE BACKGROUND ===== */
        body {
            background: linear-gradient(135deg, #fff3e6, #ffe0cc);
            font-family: 'Segoe UI', sans-serif;
        }

        /* ===== FORM CONTAINER ===== */
        .admin-container {
            max-width: 700px;
            margin: 50px auto;
            background: white;
            padding: 25px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            animation: fadeIn 0.6s ease-in-out;
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(10px);}
            to {opacity: 1; transform: translateY(0);}
        }

        /* ===== PAGE TITLE ===== */
        .page-title {
            text-align: center;
            color: #b34700;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            position: relative;
        }
        /* Add a cake emoji to the title */
        .page-title::after {
            content: "🍰";
            position: absolute;
            right: -30px;
            top: -5px;
            font-size: 28px;
        }

        /* ===== FORM FIELDS ===== */
        .product-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .product-form input[type="text"],
        .product-form input[type="number"],
        .product-form input[type="file"] {
            padding: 10px;
            border: 1px solid #d9a679;
            border-radius: 8px;
            font-size: 15px;
            background-color: #fffaf5;
            transition: 0.3s;
        }

        /* Focus effect */
        .product-form input:focus {
            border-color: #ff944d;
            box-shadow: 0 0 5px rgba(255,148,77,0.5);
        }

        .product-form label {
            font-weight: bold;
            color: #5a3e2b;
        }

        /* Product image styling */
        .product-form img {
            border-radius: 10px;
            border: 2px solid #f2d3b3;
            padding: 5px;
            background-color: #fff;
            transition: transform 0.3s;
        }
        .product-form img:hover {
            transform: scale(1.05); /* Zoom on hover */
        }

        /* ===== BUTTONS ===== */
        .btn-update {
            background: linear-gradient(135deg, #ff944d, #cc5200);
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-update:hover {
            background: linear-gradient(135deg, #cc5200, #ff944d);
            transform: translateY(-2px);
        }

        /* Back to products button */
        .btn-back {
            display: inline-block;
            background: #8c4a2f;
            color: white;
            padding: 10px 16px;
            font-size: 15px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 10px;
            transition: 0.3s;
        }
        .btn-back:hover {
            background: #6b3521;
            transform: translateY(-2px);
        }

        /* ===== MOBILE RESPONSIVE ===== */
        @media (max-width: 600px) {
            .admin-container {
                margin: 20px;
                padding: 20px;
            }
            .btn-update, .btn-back {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<!-- Main form container -->
<div class="admin-container">
    <h2 class="page-title">Edit Product</h2>

    <!-- Product Edit Form -->
    <form method="POST" enctype="multipart/form-data" class="product-form">
        <!-- Hidden field for product ID -->
        <input type="hidden" name="id" value="<?= $product['id']; ?>">

        <!-- Product name -->
        <input type="text" name="name" value="<?= $product['name']; ?>" placeholder="Product Name" required>

        <!-- Product price -->
        <input type="number" step="0.01" name="price" value="<?= $product['price']; ?>" placeholder="Price" required>

        <!-- Stock quantity -->
        <input type="number" name="stock" value="<?= $product['stock']; ?>" placeholder="Stock" required>

        <!-- Current image display -->
        <label>Current Image:</label>
        <img src="../assets/images/<?= $product['image']; ?>" width="150" height="150">

        <!-- Upload new image -->
        <label>Upload New Image (optional):</label>
        <input type="file" name="image">

        <!-- Update button -->
        <button type="submit" name="update" class="btn-update">Update Product</button>
    </form>

    <!-- Back to Manage Products -->
    <a href="manage_products.php" class="btn-back">← Back to Manage Products</a>
</div>

</body>
</html>
