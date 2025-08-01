<?php
session_start();
include 'db.php';

// Validate order_id
if (!isset($_GET['order_id'])) {
    header("Location: cus_dashboard.php");
    exit();
}

$order_id = intval($_GET['order_id']);

// Fetch order details
$orderQuery = mysqli_query($conn, "SELECT * FROM orders WHERE id = '$order_id'");
$order = mysqli_fetch_assoc($orderQuery);
$totalAmount = number_format($order['total_amount'], 2);

// Fetch order items
$itemQuery = mysqli_query($conn, "
    SELECT p.name, oi.quantity, p.price 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = '$order_id'
");
$items = [];
while ($row = mysqli_fetch_assoc($itemQuery)) {
    $items[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Summary - Thilaga Bakery</title>
<style>
    body { margin: 0; font-family: 'Poppins', sans-serif; background: #f9f6f1; color: #4a2c00; }

    header {
        background: #6b3e09; color: white;
        display: flex; justify-content: space-between; align-items: center;
        padding: 15px 40px; box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    .logo { font-size: 26px; font-weight: bold; display: flex; align-items: center; }
    .logo img { width: 30px; margin-right: 8px; }
    nav a { color: white; text-decoration: none; margin-left: 20px; font-weight: 500; }
    nav a:hover { color: #ffd9a0; }

    .container {
        max-width: 800px; margin: 50px auto; background: #fff; padding: 30px;
        border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        text-align: center;
    }
    h1 { color: #28a745; font-size: 28px; margin-bottom: 10px; }
    p { font-size: 16px; color: #333; margin-bottom: 20px; }

    .order-id { font-size: 20px; font-weight: bold; color: #6b3e09; margin-bottom: 15px; }

    table {
        width: 100%; border-collapse: collapse; margin-bottom: 20px;
    }
    th, td {
        padding: 12px; text-align: center; border-bottom: 1px solid #ddd;
    }
    th { background: #6b3e09; color: white; }
    td { font-size: 15px; }

    .total {
        text-align: right; font-size: 18px; font-weight: bold;
        margin-top: 10px; color: #6b3e09;
    }

    .btn {
        display: inline-block; background: #6b3e09; color: white;
        padding: 12px 25px; border-radius: 8px; text-decoration: none;
        font-size: 16px; transition: background 0.3s;
    }
    .btn:hover { background: #4a2c00; }

    @media (max-width: 768px) {
        .container { margin: 20px; padding: 20px; }
        table th, table td { font-size: 14px; }
    }
</style>
</head>
<body>
<header>
    <div class="logo">🍰 Thilaga Bakery</div>
    <nav>
        <a href="index.html">Home</a>
        <a href="menu.html">Menu</a>
        <a href="#">Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <h1>Thank you for your order!</h1>
    <p>Your order has been placed successfully. Here is your bill:</p>
    <div class="order-id">Order ID: #<?php echo $order_id; ?></div>

    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price (₹)</th>
                <th>Subtotal (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total">Total Amount: ₹<?php echo $totalAmount; ?></div>
    <a href="cus_dashboard.php" class="btn">Go Back to Dashboard</a>
</div>
</body>
</html>
