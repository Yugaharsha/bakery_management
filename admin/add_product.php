<?php
include '../db.php';

if (isset($_POST['submit'])) {
    $name  = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Handle uploaded image
    $imageName = $_FILES['image']['name'];
    $imageTmp  = $_FILES['image']['tmp_name'];
    $targetDir = "../assets/" . basename($imageName);

    // Move file to assets/ folder
    if (move_uploaded_file($imageTmp, $targetDir)) {
        // Insert product into database
        $stmt = $conn->prepare("INSERT INTO products (name, price, stock, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdis", $name, $price, $stock, $imageName);
        
        if ($stmt->execute()) {
            echo "<script>alert('Product added successfully!'); window.location.href='manage_products.php';</script>";
        } else {
            echo "Database error!";
        }
    } else {
        echo "<script>alert('Failed to upload image!');</script>";
    }
}
?>
