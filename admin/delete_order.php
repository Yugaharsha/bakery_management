<?php
include '../db.php';

// ✅ Check if order ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: orders.php?error=No order ID provided");
    exit();
}

$order_id = intval($_GET['id']); // Sanitize input

// ✅ Delete order from database
$query = "DELETE FROM orders WHERE id = $order_id";

if (mysqli_query($conn, $query)) {
    header("Location: orders.php?message=Order deleted successfully");
    exit();
} else {
    echo "Error deleting order: " . mysqli_error($conn);
}
?>
