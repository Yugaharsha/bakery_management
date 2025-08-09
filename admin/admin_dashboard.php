<?php
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login_page/Login_Page.php");
    exit();
}

// Fetch low stock items (less than or equal to 5)
$lowStockQuery = "SELECT name, stock FROM products WHERE stock <= 5 ORDER BY stock ASC";
$lowStockResult = mysqli_query($conn, $lowStockQuery);

// Fetch recent orders (last 5)
$recentOrdersQuery = "SELECT id, total_amount, order_date, status FROM orders ORDER BY order_date DESC LIMIT 5";
$recentOrdersResult = mysqli_query($conn, $recentOrdersQuery);

// Get total sales
$salesQuery = "SELECT SUM(total_amount) AS total_sales FROM orders";
$salesResult = mysqli_query($conn, $salesQuery);
$salesData = mysqli_fetch_assoc($salesResult);
$totalSales = $salesData['total_sales'] ?? 0;

// Get today's orders
$todaysOrdersQuery = "SELECT COUNT(*) AS count FROM orders WHERE DATE(order_date) = CURDATE()";
$todaysOrdersResult = mysqli_query($conn, $todaysOrdersQuery);
$todayData = mysqli_fetch_assoc($todaysOrdersResult);
$ordersToday = $todayData['count'] ?? 0;

// Get total products
$productQuery = "SELECT COUNT(*) AS count FROM products";
$productResult = mysqli_query($conn, $productQuery);
$productData = mysqli_fetch_assoc($productResult);
$totalProducts = $productData['count'] ?? 0;

// Get total customers
$customerQuery = "SELECT COUNT(*) AS count FROM users";
$customerResult = mysqli_query($conn, $customerQuery);
$customerData = mysqli_fetch_assoc($customerResult);
$totalCustomers = $customerData['count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bakery Admin Dashboard</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <h2>🍰 Bakery Admin</h2>
        <ul>
            <li><a href="#">📊 Dashboard</a></li>
            <li><a href="manage_products.php">🧁 Manage Products</a></li>
            <li><a href="orders.php">🛒 Orders</a></li>
            <li><a href="#">📈 Reports</a></li>
            <li><a href="#">👥 Customers</a></li>
            <li><a href="#">⚙️ Settings</a></li>
            <li><a href="../logout.php">🚪 Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="header">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?> 👋</h1>
            <p>Here's what's happening in your bakery today...</p>
        </div>

        <div class="cards">
            <div class="card">
                <h3>Total Sales</h3>
                <p>₹<?= number_format($totalSales, 2) ?></p>
                <i class="fas fa-rupee-sign icon"></i>
            </div>
            <div class="card">
                <h3>Orders Today</h3>
                <p><?= $ordersToday ?></p>
                <i class="fas fa-shopping-bag icon"></i>
            </div>
            <div class="card">
                <h3>Products</h3>
                <p><?= $totalProducts ?></p>
                <i class="fas fa-bread-slice icon"></i>
            </div>
            <div class="card">
                <h3>Customers</h3>
                <p><?= $totalCustomers ?></p>
                <i class="fas fa-users icon"></i>
            </div>
        </div>

        <div class="section">
            <h2>⚠️ Low Stock Items</h2>
            <div class="item-cards">
                <?php if (mysqli_num_rows($lowStockResult) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($lowStockResult)) { ?>
                        <div class="item-card <?= ($row['stock'] <= 2) ? 'danger' : 'warning'; ?>">
                            <span class="item-icon">🍞</span>
                            <div class="item-info">
                                <strong><?= htmlspecialchars($row['name']) ?></strong>
                                <span class="badge <?= ($row['stock'] <= 2) ? 'very-low' : 'low'; ?>">
                                    <?= $row['stock'] ?> left
                                </span>
                            </div>
                        </div>
                    <?php } ?>
                <?php else: ?>
                    <p>No low stock items 🎉</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="section">
            <h2>🕓 Recent Orders</h2>
            <div class="item-cards">
                <?php if (mysqli_num_rows($recentOrdersResult) > 0): ?>
                    <?php while ($order = mysqli_fetch_assoc($recentOrdersResult)) { ?>
                        <div class="item-card">
                            <span class="item-icon">📦</span>
                            <div class="item-info">
                                <strong>Order #<?= $order['id'] ?></strong>
                                <div>₹<?= number_format($order['total_amount'], 2) ?></div>
                                <span class="badge <?= strtolower($order['status']) === 'delivered' ? 'delivered' : 'pending' ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </div>
                        </div>
                    <?php } ?>
                <?php else: ?>
                    <p>No recent orders found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
