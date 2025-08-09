<?php
include 'db.php';
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login_page/Login_Page.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);

// ✅ Prevent duplicates
$check_sql = "SELECT * FROM favorites WHERE user_id = $user_id AND product_id = $product_id";
$result = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($result) == 0) {
    $sql = "INSERT INTO favorites (user_id, product_id) VALUES ($user_id, $product_id)";
    mysqli_query($conn, $sql);
}

// Redirect back
header("Location:../cart_page/cus_dashboard.php");
exit();
?>
