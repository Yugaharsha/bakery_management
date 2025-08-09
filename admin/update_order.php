<?php
include '../db.php';

// ✅ Check if ID is passed in URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = intval($_GET['id']); // Sanitize input

// ✅ Fetch order details
$query = "SELECT * FROM orders WHERE id = $order_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "Order not found!";
    exit();
}

$order = mysqli_fetch_assoc($result);

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    $update_query = "UPDATE orders SET status = '$new_status' WHERE id = $order_id";

    if (mysqli_query($conn, $update_query)) {
        header("Location: orders.php?message=Order Updated Successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Order</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Bakery Admin</h2>
    <ul>
         <li><a href="admin_dashboard.php">📊 Dashboard</a></li>
        <li><a href="manage_products.php">🧁 Manage Products</a></li>
        <li><a href="orders.php" style="background:#b7684e;">🛒 Orders</a></li>
        <li><a href="report.php">📈 Reports</a></li>
        <li><a href="#">👥 Customers</a></li>
        <li><a href="#">⚙️ Settings</a></li>
        <li><a href="../logout.php">🚪Logout</a></li>
    </ul>
</div>

<!-- Main content -->
<div class="main">
    <div class="header">
        <h1>Update Order #<?php echo $order['id']; ?></h1>
    </div>

    <div class="card" style="max-width: 800px; margin-top: 40px;">
        <form method="POST">
            <!-- Status Dropdown -->
            <label>Status:</label>
                <select name="status" required>
                    <option value="Pending" <?php echo ($order['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="Processing" <?php echo ($order['status'] == 'Processing') ? 'selected' : ''; ?>>Processing</option>
                    <option value="Completed" <?php echo ($order['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                    <option value="Cancelled" <?php echo ($order['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                </select>

            <br><br>
            <!-- Buttons -->
            <button type="submit" style="padding:8px 20px; background:#582f21; color:white; border:none; border-radius:5px; cursor:pointer;">Update</button>
            <a href="orders.php" style="padding:8px 20px; background:#b7684e; color:white; border:none; border-radius:5px; text-decoration:none;">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>
