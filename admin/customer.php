<?php
include '../db.php';
include '../auth_check.php';

// Handle deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id = $id");
    header("Location: customers.php");
    exit();
}

// Search functionality
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

// Fetch customers with total orders, filtered if search applied
$query = "
    SELECT u.id, u.username, u.Phone, u.address, 
           COUNT(o.id) AS total_orders
    FROM users u
    LEFT JOIN orders o ON u.id = o.user_id
    WHERE u.username LIKE '%$search%' 
       OR u.Phone LIKE '%$search%' 
       OR u.address LIKE '%$search%'
    GROUP BY u.id
    ORDER BY u.username ASC
";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Management</title>
    <link rel="stylesheet" href="admin_style.css"> <!-- Your theme CSS -->
    <style>
        .btn-delete {
            display: inline-block;
            padding: 6px 14px;
            background-color: #ef5350;
            color: white;
            border-radius: 6px;
            font-size: 14px;
            text-decoration: none;
            transition: background-color 0.2s ease-in-out;
        }
        .btn-delete:hover {
            background-color: #d32f2f;
        }
        .search-bar {
            margin-bottom: 15px;
            display: flex;
            gap: 10px;
        }
        .search-bar input {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
            flex: 1;
            background-color: #fffaf5;
        }
        .search-bar button {
            background-color: #b7684e;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .search-bar button:hover {
            background-color: #8d4f3d;
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
            <li><a href="admin_manage.php">🧑‍🍳 Manage Admins</a></li>
            <li><a href="../logout.php">🚪 Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main">
    <div class="header">
        <h1>Customer Management</h1>
    </div>

    <!-- Search Bar -->
    <form method="GET" class="search-bar">
        <input type="text" name="search" placeholder="Search by name, phone, or address" value="<?= htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>

    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Total Orders</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= htmlspecialchars($row['username']); ?></td>
            <td><?= htmlspecialchars($row['Phone']); ?></td>
            <td><?= htmlspecialchars($row['address']); ?></td>
            <td>
                <span class="badge delivered"><?= $row['total_orders']; ?></span>
            </td>
            <td>
                <a href="customers.php?delete=<?= $row['id']; ?>" 
                   onclick="return confirm('Are you sure you want to delete this customer?')" 
                   class="btn-delete">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
