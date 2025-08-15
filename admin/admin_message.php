<?php
include '../db.php';
include '../auth_check.php';

// ✅ Ensure we have a database connection variable
if (!isset($mysqli) && isset($conn)) {
    $mysqli = $conn; // Fallback if db.php uses $conn instead of $mysqli
}

// If still no connection, stop execution
if (!isset($mysqli) || !$mysqli) {
    die("Database connection not found. Please check db.php");
}

// ✅ Update status if admin clicks a button
if (isset($_GET['mark']) && isset($_GET['id'])) {
    $status = $_GET['mark'];
    $id = intval($_GET['id']);

    $stmt = $mysqli->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin_message.php");
    exit;
}

// ✅ Fetch all messages
$result = $mysqli->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - View Messages</title>
    <link rel="stylesheet" href="admin_style.css"> <!-- your admin CSS file -->
    <style>
        /* Push content to the right of sidebar */
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        /* Fix table layout */
        .styled-table {
            border-collapse: collapse;
            width: 100%;
        }
        .styled-table th, .styled-table td {
            text-align: left;
            padding: 8px 12px;
            vertical-align: top; /* Align text to top */
            white-space: normal; /* Allow text wrapping */
        }
        .styled-table th {
            background-color: #6b3e2e;
            color: #fff;
        }
        .btn {
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn-success {
            background-color: #28a745;
            color: white;
            padding: 2px 4px;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 2px 4px;
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

    <!-- Main content -->
    <div class="main-content">
        <h1>📩 Customer Messages</h1>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= nl2br(htmlspecialchars(trim($row['message']))) ?></td>
                        <td><?= $row['submitted_at'] ?></td>
                        <td><?= $row['status'] ?: "Pending" ?></td>
                        <td>
                            <a class="btn btn-success" href="?mark=Read&id=<?= $row['id'] ?>">Mark Read</a>
                            <a class="btn btn-primary" href="?mark=Replied&id=<?= $row['id'] ?>">Mark pending</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
