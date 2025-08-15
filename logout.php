<?php
// Start the session
session_start();

// Stop caching (so back button won’t load old pages)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Unset all session variables
$_SESSION = array();

// If using cookies, delete the session cookie too
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to login page (change path if needed)
header("Location: login_page/Login_Page.php");
exit();
?>
