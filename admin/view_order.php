<?php
include '../db.php';
include '../auth_check.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid order ID");
}
$order_id = intval($_GET['id']);

$order_query = "
SELECT 
    o.id, o.order_date, o.total_amount, o.status,
    u.username AS customer_name, u.phone, u.address
FROM orders o
JOIN users u ON o.user_id = u.id
WHERE o.id = $order_id
";
$order_result = mysqli_query($conn, $order_query);
if (!$order_result || mysqli_num_rows($order_result) === 0) {
    die("Order not found.");
}
$order = mysqli_fetch_assoc($order_result);

$items_query = "
SELECT 
    p.name AS product_name, p.price, oi.quantity
FROM order_items oi
JOIN products p ON oi.product_id = p.id
WHERE oi.order_id = $order_id
";
$items_result = mysqli_query($conn, $items_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order #<?= $order['id']; ?> - Invoice</title>
    <link rel="stylesheet" href="admin_style.css">

    <style>
       body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #faf6f2;
    margin: 0;
    padding: 0;
}

.main {
    padding: 20px;
}

#print-area {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    max-width: 800px;
    margin: auto;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

#print-area h2 {
    font-size: 28px;
    color: #a0522d;
    margin-bottom: 5px;
}

#print-area hr {
    border: 1px solid #e0c9a6;
    margin: 15px 0;
}

#print-area h3 {
    color: #a0522d;
    margin-top: 20px;
}

#print-area p {
    font-size: 16px;
    margin: 5px 0;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.admin-table th {
    background: #f4e3d7;
    color: #5c3b21;
    padding: 10px;
    text-align: left;
}

.admin-table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.admin-table tr:nth-child(even) {
    background-color: #f9f5f0;
}

.btn-print {
    background: #28a745;
    color: white;
    padding: 8px 15px;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
}

.btn-print:hover {
    background: #218838;
}

.btn-back {
    background: #6c757d;
    color: white;
    padding: 8px 15px;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
}

.btn-back:hover {
    background: #5a6268;
}

@media print {
    body {
        background: white;
    }
    body * {
        visibility: hidden;
    }
    #print-area, #print-area * {
        visibility: visible;
    }
    #print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .btn-print, .btn-back, .sidebar {
        display: none;
    }
}

    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Bakery Admin</h2>
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

<!-- Main Content -->
<div class="main">
    <div style="margin-bottom: 20px;">
        <a href="#" class="btn-print" onclick="window.print()">🖨 Print Invoice</a>
        <a href="orders.php" class="btn-back">← Back to Orders</a>
    </div>

    <!-- Print Area -->
    <div id="print-area">
        <h2 style="text-align:center;">Bakery Invoice</h2>
        <hr>

        <h3>Customer Information</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']); ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']); ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($order['address']); ?></p>

        <h3>Order Information</h3>
        <p><strong>Date:</strong> <?= date("d M Y, h:i A", strtotime($order['order_date'])); ?></p>
        <p><strong>Total:</strong> ₹<?= number_format($order['total_amount'], 2); ?></p>

        <h3>Ordered Items</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price (₹)</th>
                    <th>Quantity</th>
                    <th>Subtotal (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $grand_total = 0;
                mysqli_data_seek($items_result, 0);
                while ($item = mysqli_fetch_assoc($items_result)) { 
                    $subtotal = $item['price'] * $item['quantity'];
                    $grand_total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']); ?></td>
                    <td><?= number_format($item['price'], 2); ?></td>
                    <td><?= $item['quantity']; ?></td>
                    <td><?= number_format($subtotal, 2); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3 style="text-align:right;">Grand Total: ₹<?= number_format($grand_total, 2); ?></h3>
    </div>
</div>

</body>
</html>
