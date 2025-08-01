<?php
session_start();
include 'db.php'; // Your DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Fetch user details from DB
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $row['password'])) {
            // ✅ Set session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // ✅ Redirect to customer dashboard
            header("Location: cus_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('User not found!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request!'); window.history.back();</script>";
}
?>
