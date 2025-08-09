<?php
session_start(); // Start the session to access session variables
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

header("Location:login_page/Login_Page.php"); // Update path if needed
exit();
?>
