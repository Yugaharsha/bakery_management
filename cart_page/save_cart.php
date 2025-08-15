<?php
session_start();

// ✅ Save the cart from JS to PHP session
if (isset($_POST['cart'])) {
    $_SESSION['cart'] = json_decode($_POST['cart'], true);
}
?>
