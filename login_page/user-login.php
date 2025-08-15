<?php
session_start();
include '../db.php';  // Use forward slashes for portability

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "<script>alert('Please enter all fields!'); window.history.back();</script>";
        exit();
    }

    // Step 1: Check in admin table
    $admin_stmt = $conn->prepare("SELECT id, username, password, role FROM admin WHERE username = ?");
    $admin_stmt->bind_param("s", $username);
    $admin_stmt->execute();
    $admin_result = $admin_stmt->get_result();

    if ($admin_result->num_rows > 0) {
        $admin = $admin_result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            // ✅ Store session variables for admin
            $_SESSION['admin_id']       = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_role']     = $admin['role']; // <-- Added this line

            header("Location: ../admin/admin_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.history.back();</script>";
            exit();
        }
    }

    // Step 2: Check in users table
    $user_stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $user_stmt->bind_param("s", $username);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();

    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: ../cart_page/cus_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('Kindly Register!'); window.history.back();</script>";
        exit();
    }
}
?>
