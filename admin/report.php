<?php
// Include database connection
include '../db.php';
include '../auth_check.php';
/*
    --- REPORT QUERIES ---
*/
// 1️⃣ Today's Sales Summary
$today = date('Y-m-d');
$query_sales_today = "
    SELECT COUNT(*) AS total_orders, 
           SUM(total_amount) AS total_revenue
    FROM orders
    WHERE DATE(order_date) = '$today'
";
$sales_today = mysqli_fetch_assoc(mysqli_query($conn, $query_sales_today));

// 2️⃣ Top 5 Best-Selling Products
$query_top_products = "
    SELECT p.name, SUM(oi.quantity) AS total_sold
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    GROUP BY p.name
    ORDER BY total_sold DESC
    LIMIT 5
";
$top_products = mysqli_query($conn, $query_top_products);

// 3️⃣ Orders by Status
$query_orders_status = "
    SELECT status, COUNT(*) AS count
    FROM orders
    GROUP BY status
";
$orders_status = mysqli_query($conn, $query_orders_status);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports - Bakery Management</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
            <li><a href="admin_dashboard.php">📊 Dashboard</a></li>
            <li><a href="manage_products.php">🧁 Manage Products</a></li>
            <li><a href="orders.php">🛒 Orders</a></li>
            <li><a href="report.php">📈 Reports</a></li>
            <li><a href="customer.php">👥 Customers</a></li>
            <li><a href="admin_message.php">📩 Messages</a></li>
            <li><a href="admin_manage.php">🧑‍🍳 Manage Admins</a></li>
            <li><a href="../logout.php">🚪 Logout</a></li>
    </ul>
</div>

<!-- Main content -->
<div class="main">
    <h1>📊 Reports</h1>

    <!-- Daily Sales Summary -->
    <div class="cards">
        <div class="card">
            <h3>Today's Orders</h3>
            <p><?= $sales_today['total_orders'] ?? 0; ?></p>
        </div>
        <div class="card">
            <h3>Today's Revenue (₹)</h3>
            <p><?= number_format($sales_today['total_revenue'] ?? 0, 2); ?></p>
        </div>
    </div>

    <!-- Top Products -->
    <div class="section">
        <h2>🍞 Top 5 Best-Selling Products</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Total Sold</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($top_products)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= $row['total_sold']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Orders by Status -->
    <div class="section">
        <h2>📦 Orders by Status</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($orders_status)) { ?>
                    <tr>
                        <td><?= $row['status']; ?></td>
                        <td><?= $row['count']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
