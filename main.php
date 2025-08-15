<?php
require 'db.php';
$username = 'mainadmin';            // change to your preferred username
$plain = 'Main@12345';              // change to a strong password

$hash = password_hash($plain, PASSWORD_BCRYPT);
$stmt = $conn->prepare("INSERT INTO admin (username, password, role) VALUES (?, ?, 'main')");
$stmt->bind_param("ss", $username, $hash);
if ($stmt->execute()) {
  echo "Main admin created. Username: $username";
} else {
  echo "Error: ".$conn->error;
}
