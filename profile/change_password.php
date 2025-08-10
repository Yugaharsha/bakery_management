<?php

include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location:../login_page/Login_Page.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Fetch current password
$sql = "SELECT password FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if (!password_verify($current_password, $user['password'])) {
    die("Current password is incorrect!");
}

if ($new_password !== $confirm_password) {
    die("New passwords do not match!");
}

$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$update_sql = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";

if (mysqli_query($conn, $update_sql)) {
    header("Location: profile.php?success=Password changed successfully");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
