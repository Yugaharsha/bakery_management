<?php

include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location:../login_page/Login_Page.php");
    exit();
}

$fav_id = intval($_POST['fav_id']);
$deleteQuery = "DELETE FROM favorites WHERE id = $fav_id";
mysqli_query($conn, $deleteQuery);

header("Location: profile.php");
exit();
?>
