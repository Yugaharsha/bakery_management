<?php
// Include database connection
include '../db.php';
include '../auth_check.php';

/*
   Fetch orders with customer details
   - orders (o) → main order info
   - users (u) → customer info
*/
$query = "
SELECT 
    o.id, 
    u.username AS customer_name, 
    u.phone AS customer_phone, 
    u.address AS customer_address, 
    o.order_date, 
    o.total_amount, 
    o.status
FROM orders o
JOIN users u ON o.user_id = u.id
ORDER BY o.order_date DESC
";

$result = mysqli_query($conn, $query);

// If query fails, stop script
if (!$result) {
    die('Query Failed: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <link rel="stylesheet" href="admin_style.css"> <!-- Link to your existing theme -->
    <style>
        /* Action buttons */
.btn-edit, .btn-update, .btn-delete {
    padding: 4px 2px;
    text-decoration: none;
    border-radius: 4px;
    color: white;
    margin-right: 5px;
}

.btn-edit { background-color: #391c0685; }
.btn-update { background-color: #d1a90cff; color: black; }
.btn-delete { background-color: #e32b17ff; }

.btn-edit:hover { background-color: #853f09cb; }
.btn-update:hover { background-color: #e6ce6eff; }
.btn-delete:hover { background-color: #c0392b; }

    </style>
</head>
<body>

<!-- Sidebar Navigation -->
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
    <h1 class="page-title">Manage Orders</h1>

    <!-- Orders Table -->
    <table class="admin-table" border="1" cellpadding="10" cellspacing="0" style="width:100%; background:#fff9f1; border-collapse: collapse;">
        <thead style="background:#582f21; color:#fff;">
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Date</th>
                <th>Total (₹)</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['customer_name']); ?></td>
                <td><?= htmlspecialchars($row['customer_phone']); ?></td>
                <td><?= htmlspecialchars($row['customer_address']); ?></td>
                <td><?= date("d M Y, h:i A", strtotime($row['order_date'])); ?></td>
                <td><?= number_format($row['total_amount'], 2); ?></td>
                <td>
                    <!-- Apply status styles from CSS -->
                    <span class="status <?= strtolower($row['status']); ?>">
                        <?= $row['status']; ?>
                    </span>
                </td>
                <td>
                    <!-- Action Buttons -->
                    <a href="view_order.php?id=<?= $row['id']; ?>" class="btn-edit">View</a>
                    <a href="update_order.php?id=<?= $row['id']; ?>" class="btn-update">Update</a>
                    <a href="delete_order.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this order?');" class="btn-delete">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
