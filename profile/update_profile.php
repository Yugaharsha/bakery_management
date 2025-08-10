<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login_page/Login_Page.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);  // user_id is integer, keep intval here

// Check if all required POST fields are present and not empty
if (
    empty($_POST['username']) ||
    empty($_POST['Phone']) ||   // Phone as string, don't use intval here
    empty($_POST['address'])
) {
    die("Please fill all required fields.");
}

$username = trim($_POST['username']);
$phone = trim($_POST['Phone']);  // treat as string, no intval
$address = trim($_POST['address']);

// Prepare SQL statement to update user profile
$stmt = $conn->prepare("UPDATE users SET username = ?, Phone = ?, address = ? WHERE id = ?");
$stmt->bind_param("sssi", $username, $phone, $address, $user_id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: profile.php?success=Profile updated successfully");
    exit();
} else {
    echo "Error updating profile: " . htmlspecialchars($stmt->error);
}
?>
