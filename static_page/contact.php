<?php
// Database connection
$mysqli = new mysqli("localhost", "root", "", "bakery_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    $stmt = $mysqli->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        $success = "Message sent successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us - Thilaga Bakery</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f1e4;
        margin: 0;
        padding: 0;
    }
    /* Navigation bar */
    nav {
        background-color: #8b4513;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 30px;
    }
    nav .logo {
        font-size: 24px;
        font-weight: bold;
        color: white;
    }
    nav ul {
        list-style: none;
        display: flex;
        gap: 20px;
        margin: 0;
        padding: 0;
    }
    nav ul li {
        display: inline;
    }
    nav ul li a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        transition: color 0.3s;
    }
    nav ul li a:hover {
        color: #ffdd99;
    }

    /* Layout */
    .container {
        display: flex;
        gap: 20px;
        padding: 30px;
        max-width: 1200px;
        margin: auto;
    }
    .contact-info, .contact-form {
        background: white;
        border-radius: 15px;
        padding: 25px;
        flex: 1;
        box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
    }
    .contact-info h2, .contact-form h2 {
        color: #8b4513;
    }
    .form-group {
        margin-bottom: 15px;
    }
    input, textarea {
        width: 100%;
        padding: 10px;
        border: 2px solid #d9b38c;
        border-radius: 10px;
        font-size: 16px;
    }
    button {
        background-color: #8b4513;
        color: white;
        border: none;
        padding: 12px 20px;
        font-size: 16px;
        border-radius: 10px;
        cursor: pointer;
    }
    button:hover {
        background-color: #6a3410;
    }
    .map-container {
        margin-top: 30px;
    }
    iframe {
        width: 100%;
        height: 400px;
        border: none;
    }
    .success { color: green; }
    .error { color: red; }
</style>
</head>
<body>

<!-- Navbar -->
<nav>
    <div class="logo">🍰 Thilaga Bakery</div>
    <ul>
        <li><a href="../index.html">Home</a></li>
        <li><a href="menu.php">Menu</a></li>
        <li><a href="aboutpage.html">About</a></li>
    </ul>
</nav>

<div class="container">
    <!-- Contact Info -->
    <div class="contact-info">
        <h2>📍 Contact Info</h2>
        <p><b>Address:</b> Gandhi's Thilaga Bakery & Sweets, Madurai</p>
        <p><b>Phone:</b> +91 98765 43210</p>
        <p><b>Email:</b> info@thilagabakery.com</p>
        <p><b>Hours:</b> 8:00 AM - 9:00 PM</p>
    </div>

    <!-- Contact Form -->
    <div class="contact-form">
        <h2>💬 Send us a message</h2>
        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="name" placeholder="Your Name" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Your Email" required>
            </div>
            <div class="form-group">
                <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
            </div>
            <button type="submit">Send Message</button>
        </form>
    </div>
</div>

<!-- Full-Width Map -->
<div class="map-container">
    <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3930.1502446217956!2d78.1077622!3d9.9326806!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3b00cf603169e21b%3A0xd7b443cc6f2fb39!2sGandhi's%20Thilaga%20Bakery%20%26%20Sweets!5e0!3m2!1sen!2sin!4v1691666666666" 
        allowfullscreen="" loading="lazy">
    </iframe>
</div>

</body>
</html>
