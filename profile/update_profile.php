<?php

include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login_page/Login_Page.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = mysqli_real_escape_string($conn, $_POST['username']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$address = mysqli_real_escape_string($conn, $_POST['address']);

$sql = "UPDATE users SET username='$username', phone='$phone', address='$address' WHERE id=$user_id";

if (mysqli_query($conn, $sql)) {
    header("Location: profile.php?success=Profile updated successfully");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
