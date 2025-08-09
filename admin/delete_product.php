<?php
include '../db.php'; // DB connection

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize product ID

    // Get image name before deleting product
    $imgQuery = "SELECT image FROM products WHERE id = $id";
    $imgResult = mysqli_query($conn, $imgQuery);
    $imgRow = mysqli_fetch_assoc($imgResult);

    // If image exists, delete from folder
    if ($imgRow && !empty($imgRow['image']) && file_exists("../assets/images/" . $imgRow['image'])) {
        unlink("../assets/images/" . $imgRow['image']);
    }

    // Delete product from database
    $sql = "DELETE FROM products WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: manage_products.php?msg=deleted");
        exit();
    } else {
        echo "❌ Error deleting product: " . mysqli_error($conn);
    }
} else {
    echo "❌ No product ID provided.";
}
?>
