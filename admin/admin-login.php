<?php
include '../db.php';

$admin_user = 'admin'; // You can change this
$plain_password = 'admin123'; // Password you will use to log in
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

// Check if admin already exists
$check = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$admin_user'");
if (mysqli_num_rows($check) > 0) {
    echo "Admin already exists.";
} else {
    $sql = "INSERT INTO admin (username, password) VALUES ('$admin_user', '$hashed_password')";
    if (mysqli_query($conn, $sql)) {
        echo "✅ Admin user created successfully!";
    } else {
        echo "❌ Error: " . mysqli_error($conn);
    }
}
?>
