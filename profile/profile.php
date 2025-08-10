<?php
include '../db.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login_page/Login_Page.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$user_sql = "SELECT username, phone, address FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);

// Fetch favorites
$fav_sql = "SELECT favorites.id AS fav_id, products.name, products.price, products.image
            FROM favorites
            JOIN products ON favorites.product_id = products.id
            WHERE favorites.user_id = $user_id";
$fav_result = mysqli_query($conn, $fav_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Profile - Thilaga Bakery</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background:#f8f3e7;
        margin:0;
        padding:0;
    }
    header {
        background:#6b3e09;
        color:#fff;
        padding:15px 30px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        box-shadow:0 4px 10px rgba(0,0,0,0.3);
    }
    header h1 {
        margin:0;
        font-size:26px;
    }
    header nav a {
        color:white;
        text-decoration:none;
        margin-left:20px;
        font-weight:500;
        font-size:16px;
        transition:0.3s;
    }
    header nav a:hover {
        color:#ffe1b3;
    }

    .container {
        width:90%;
        max-width:1100px;
        margin:40px auto;
        display:flex;
        flex-direction:column;
        gap:30px;
    }

    h2 {
        color:#6b3e09;
        text-align:center;
        margin-bottom:10px;
    }

    .section {
        background:#fff;
        padding:25px;
        border-radius:12px;
        box-shadow:0 6px 15px rgba(0,0,0,0.1);
    }

    .section h3 {
        color:#6b3e09;
        margin-bottom:15px;
        text-align:center;
    }

    form input, form textarea {
        width:100%;
        padding:12px;
        margin:8px 0;
        border:1px solid #ccc;
        border-radius:8px;
        font-size:16px;
    }

    form button {
        background:#6b3e09;
        color:#fff;
        padding:12px;
        border:none;
        border-radius:8px;
        font-size:16px;
        cursor:pointer;
        width:100%;
        transition:background 0.3s ease;
    }
    form button:hover {
        background:#4a2c00;
    }

    /* Favorites Styling */
    .favorites-container {
        display:grid;
        grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
        gap:20px;
    }
    .favorite-item {
        background:#fff;
        border-radius:12px;
        box-shadow:0 4px 10px rgba(0,0,0,0.1);
        padding:15px;
        text-align:center;
        transition:transform 0.3s ease;
    }
    .favorite-item:hover {
        transform:translateY(-5px);
    }
    .favorite-item img {
        width:100%;
        height:150px;
        object-fit:cover;
        border-radius:8px;
    }
    .favorite-item h4 {
        margin:10px 0 5px;
        font-size:18px;
        color:#333;
    }
    .favorite-item p {
        font-size:16px;
        color:#6b3e09;
        font-weight:bold;
    }
    .remove-btn {
        background:red;
        margin-top:8px;
        padding:8px 12px;
        border:none;
        border-radius:8px;
        color:#fff;
        font-size:14px;
        cursor:pointer;
    }
    .remove-btn:hover {
        background:darkred;
    }

    @media(max-width:768px) {
        header h1 {
            font-size:20px;
        }
        .container {
            width:95%;
        }
    }
</style>
</head>
<body>

<header>
    <h1>🍰 Thilaga Bakery</h1>
    <nav>
        <a href="../cart_page/cus_dashboard.php">Dashboard</a>
        <a href="../Logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <h2 style="text-align:center; margin-bottom:20px; color:#6b3e09;">My Profile</h2>

    <!-- ✅ Profile Edit & Change Password in Two Columns -->
    <div style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:30px;">
        
        <!-- ✅ Update Profile -->
        <div style="flex:1; min-width:300px; background:#fff8ef; padding:20px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1);">
            <h3 style="color:#6b3e09; margin-bottom:15px;">Edit Profile</h3>
            <form action="update_profile.php" method="POST">
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" 
                       style="width:100%; padding:10px; margin-bottom:12px; border:1px solid #ccc; border-radius:6px;" required>
                <input type="text" name="Phone" value="<?php echo htmlspecialchars($user['phone']); ?>" 
                       style="width:100%; padding:10px; margin-bottom:12px; border:1px solid #ccc; border-radius:6px;" required>
                <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" 
                       style="width:100%; padding:10px; margin-bottom:12px; border:1px solid #ccc; border-radius:6px;" required>
                <button type="submit" 
                        style="background:#6b3e09; color:#fff; border:none; padding:12px; width:100%; border-radius:6px; font-weight:bold;">
                    Update Profile
                </button>
            </form>
        </div>

        <!-- ✅ Change Password -->
        <div style="flex:1; min-width:300px; background:#fff8ef; padding:20px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1);">
            <h3 style="color:#6b3e09; margin-bottom:15px;">Change Password</h3>
            <form action="change_password.php" method="POST">
                <input type="password" name="current_password" placeholder="Current Password" 
                       style="width:100%; padding:10px; margin-bottom:12px; border:1px solid #ccc; border-radius:6px;" required>
                <input type="password" name="new_password" placeholder="New Password" 
                       style="width:100%; padding:10px; margin-bottom:12px; border:1px solid #ccc; border-radius:6px;" required>
                <input type="password" name="confirm_password" placeholder="Confirm New Password" 
                       style="width:100%; padding:10px; margin-bottom:12px; border:1px solid #ccc; border-radius:6px;" required>
                <button type="submit" 
                        style="background:#6b3e09; color:#fff; border:none; padding:12px; width:100%; border-radius:6px; font-weight:bold;">
                    Change Password
                </button>
            </form>
        </div>
    </div>

    <!-- ✅ Favorites Section -->
    <div class="section">
        <h3>Your Favorites</h3>
        <?php if (mysqli_num_rows($fav_result) > 0): ?>
            <table style="width:100%; border-collapse:collapse; margin-top:15px;">
                <thead>
                    <tr style="background:#6b3e09; color:#fff; text-align:left;">
                        <th style="padding:10px;">Image</th>
                        <th style="padding:10px;">Product Name</th>
                        <th style="padding:10px;">Price</th>
                        <th style="padding:10px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($fav = mysqli_fetch_assoc($fav_result)): ?>
                    <tr style="border-bottom:1px solid #ddd;">
                        <td style="padding:10px; text-align:center;">
                            <img src="../assets/images/<?php echo htmlspecialchars($fav['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($fav['name']); ?>" 
                                 style="width:60px; height:60px; border-radius:6px; object-fit:cover;">
                        </td>
                        <td style="padding:10px; font-weight:500; color:#333;"><?php echo htmlspecialchars($fav['name']); ?></td>
                        <td style="padding:10px; color:#6b3e09; font-weight:bold;">₹<?php echo htmlspecialchars($fav['price']); ?></td>
                        <td style="padding:10px;">
                            <form action="remove_favorite.php" method="POST" style="display:inline;">
                                <input type="hidden" name="fav_id" value="<?php echo $fav['fav_id']; ?>">
                                <button type="submit" 
                                        style="background:red; color:#fff; border:none; padding:6px 10px; border-radius:6px; cursor:pointer;">
                                    Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align:center;">No favorites added yet!</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
