<?php

include '..\db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit();
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE Phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Phone already registered!'); window.history.back();</script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $username = $first_name . " " . $last_name;

    $stmt = $conn->prepare("INSERT INTO users (username, Phone, address, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $phone, $address, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Please login.'); window.location.href='Login_Page.php';</script>";
    } else {
        echo "<script>alert('Error: Could not register.'); window.history.back();</script>";
    }
}
?>
