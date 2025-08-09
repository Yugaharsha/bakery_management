<?php
include '../db.php';

if (isset($_POST['update'])) {
    $id    = $_POST['id'];
    $name  = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Check for new image upload
    if (!empty($_FILES['image']['name'])) {
        $imageName = $_FILES['image']['name'];
        $imageTmp  = $_FILES['image']['tmp_name'];
        $targetDir = "../assets/" . basename($imageName);
        
        if (move_uploaded_file($imageTmp, $targetDir)) {
            $imageUpdate = ", image='$imageName'";
        } else {
            echo "<script>alert('Image upload failed.'); window.history.back();</script>";
            exit();
        }
    } else {
        $imageUpdate = "";
    }

    // Update query
    $updateQuery = "UPDATE products SET name='$name', price='$price', stock='$stock' $imageUpdate WHERE id=$id";
    
    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Product updated successfully.'); window.location.href='manage_products.php';</script>";
    } else {
        echo "Update failed!";
    }
}
?>
