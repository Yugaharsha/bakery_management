<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $cart = json_decode($_POST['cart'], true);
    $total = $_POST['total'];

    if (empty($cart)) {
        echo "cus_dashboard.php";
        exit();
    }

    // Insert order
    $query = "INSERT INTO orders (user_id, total_amount, order_date) VALUES ('$user_id', '$total', NOW())";
    mysqli_query($conn, $query);
    $order_id = mysqli_insert_id($conn);

    // Insert order items & update stock
    foreach ($cart as $name => $item) {
        $quantity = $item['quantity'];
        $price = $item['price'];

        // Get product details
        $res = mysqli_query($conn, "SELECT id, stock FROM products WHERE name='$name'");
        $prod = mysqli_fetch_assoc($res);
        $product_id = $prod['id'];
        $stock = $prod['stock'];

        // Insert into order_items
        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity) VALUES ('$order_id', '$product_id', '$quantity')");

        // Update stock
        $newStock = max(0, $stock - $quantity);
        mysqli_query($conn, "UPDATE products SET stock='$newStock' WHERE id='$product_id'");
    }

    // ✅ Send redirect URL
    echo "thankyou.php?order_id=$order_id";
    exit();
}
?>
