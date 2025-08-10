<?php
include '../db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login_page/Login_Page.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// ✅ Handle Add to Favorites (No Navigation)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    // Check if already in favorites
    $check_sql = "SELECT id FROM favorites WHERE user_id=$user_id AND product_id=$product_id";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) == 0) {
        $insert_sql = "INSERT INTO favorites (user_id, product_id) VALUES ($user_id, $product_id)";
        mysqli_query($conn, $insert_sql);
    }

    // Redirect back to avoid resubmission
    header("Location: cus_dashboard.php");
    exit();
}

// Fetch products from DB
$products = [];
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
$favorites = [];
$fav_sql = "SELECT product_id FROM favorites WHERE user_id = ".$_SESSION['user_id'];
$fav_result = mysqli_query($conn, $fav_sql);
while ($fav = mysqli_fetch_assoc($fav_result)) {
    $favorites[] = $fav['product_id'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Dashboard - Thilaga Bakery</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { background-color: #f8f3e7; color: #4a2c00; }

    header {
        width: 100%; background-color: #6b3e09; color: white;
        display: flex; justify-content: space-between; align-items: center;
        padding: 15px 50px; position: fixed; top: 0; left: 0;
        z-index: 1000; box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    .logo { font-size: 26px; font-weight: bold; }
    nav a { text-decoration: none; color: white; margin-left: 20px; font-size: 16px; transition: 0.3s; }
    nav a:hover { color: #ffe1b3; }

    .hero-banner {
        width: 100%; background: linear-gradient(135deg, #6b3e09, #e2c089);
        color: #fff; text-align: center; padding: 40px 20px;
        font-family: 'Poppins', sans-serif; border-bottom-left-radius: 50px;
        border-bottom-right-radius: 50px; margin-top: 80px; margin-bottom: 20px;
    }
    .hero-banner h1 { font-size: 2.5rem; margin-bottom: 10px; font-weight: bold; }
    .hero-banner p { font-size: 1.2rem; margin-top: 0; }

    .dashboard-container {
        display: flex; justify-content: space-between; margin: 40px auto;
        width: 90%; max-width: 1200px; gap: 30px;
    }

    .menu-container {
        flex: 2; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;
    }
    .menu-item {
        background: #fff; border-radius: 12px; box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        overflow: hidden; text-align: center; padding: 15px; transition: transform 0.3s ease;
    }
    .menu-item img { width: 100%; height: 180px; object-fit: cover; border-radius: 10px; }
    .menu-item h3 { margin: 10px 0; font-size: 18px; }
    .menu-item p { font-size: 16px; color: #6b3e09; font-weight: bold; }
    .menu-item button {
        margin-top: 10px; padding: 10px 15px; background: #6b3e09; color: white;
        border: none; border-radius: 8px; cursor: pointer; font-size: 14px;
    }
    .menu-item button:hover { background: #4a2c00; }

    .cart-section {
        flex: 1; background: #fff; border-radius: 12px; box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        padding: 20px; height: fit-content; position: sticky; top: 120px;
    }
    .cart-section h2 { margin-bottom: 15px; font-size: 20px; text-align: center; color: #6b3e09; }
    .cart-items { max-height: 300px; overflow-y: auto; margin-bottom: 15px; }
    .cart-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 14px; }
    .cart-item button { margin-left: 10px; background: none; border: none; color: red; font-size: 16px; cursor: pointer; }
    .total { font-size: 18px; font-weight: bold; text-align: center; margin-top: 10px; }
    .checkout-btn {
        display: block; width: 100%; padding: 12px; background: #6b3e09; color: white;
        border: none; font-size: 16px; border-radius: 8px; cursor: pointer; margin-top: 10px;
    }
    .checkout-btn:hover { background: #4a2c00; }

    @media(max-width: 900px) { .dashboard-container { flex-direction: column; } }
</style>
</head>
<body>

<header>
    <div class="logo">🍰 Thilaga Bakery</div>
    <nav>
        <a href="../index.html">Home</a>
        <a href="../static_page/menu.php">Menu</a>
        <a href="../profile/profile.php">Profile</a>
        <a href="../logout.php">Logout</a>
    </nav>
</header>

<div class="hero-banner">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Brighten your mood with our freshly baked snacks and desserts. Order now and indulge in happiness!</p>
</div>

<div class="dashboard-container">
    <!-- ✅ Products Section -->

<div class="menu-container">
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
            <div class="menu-item">
                <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width:100%; height:180px; object-fit:cover; border-radius:10px;">

                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p>₹<?php echo htmlspecialchars($product['price']); ?></p>

                <?php if ($product['stock'] > 0): ?>
                    <?php if ($product['stock'] <= 5): ?>
                        <p style="color:red; font-size:14px;">Only <?php echo $product['stock']; ?> left!</p>
                    <?php endif; ?>
                    <button onclick="addToCart('<?php echo addslashes($product['name']); ?>', <?php echo $product['price']; ?>)">Add to Cart</button>
                <?php else: ?>
                    <button disabled style="background: gray;">Out of Stock</button>
                <?php endif; ?>

                <!-- ❤️ Add to Favorites OR Indicate Already Added -->
                <?php if (in_array($product['id'], $favorites)): ?>
                    <button style="background:green; color:#fff; border:none; padding:8px 12px; border-radius:8px; cursor:pointer;">
                        ❤️ Added to Favorites
                    </button>
                <?php else: ?>
                   <form method="POST" style="margin-top:10px;">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" style="background:#6b3e09; color:#fff; border:none; padding:8px 12px; border-radius:8px; cursor:pointer;">
                    ❤️ Add to Favorites
                    </button>
                   </form>

                <?php endif; ?>
            </div>
        <?php endforeach; ?> 
    <?php else: ?>
        <p>No products available right now!</p>
    <?php endif; ?>
</div>
<!-- ✅ Hidden Form -->
<form id="checkoutForm" method="POST" action="cus_dashboard.php" style="display:none;">
    <input type="hidden" name="cart" id="cartInput">
    <input type="hidden" name="total" id="totalInput">
</form>

<!-- ✅ Proceed to Checkout Button -->
<div class="cart-section">
    <h2>Your Cart</h2>
    <div class="cart-items" id="cartItems"></div>
    <div class="total">Total: ₹<span id="totalAmount">0</span></div>
    <button class="checkout-btn" onclick="checkout()">Proceed to Checkout</button>
</div>

<script>

let cart = {}; // Use object for unique items
let total = 0;

function checkout() {
    // Get cart items from JavaScript variable or DOM
    let cartData = JSON.parse(localStorage.getItem('cart')) || {};  // Assuming cart stored in localStorage
    let totalAmount = document.getElementById('totalAmount').innerText;

    if (Object.keys(cartData).length === 0) {
        alert('Your cart is empty!');
        return;
    }

    // Set values in hidden form
    document.getElementById('cartInput').value = JSON.stringify(cartData);
    document.getElementById('totalInput').value = totalAmount;

    // Submit the form
    document.getElementById('checkoutForm').submit();
}
function addToCart(name, price) {
    if (cart[name]) {
        cart[name].quantity++;
    } else {
        cart[name] = { price: price, quantity: 1 };
    }
    calculateTotal();
    displayCart();
}

function removeFromCart(name) {
    if (cart[name]) {
        total -= cart[name].price * cart[name].quantity;
        delete cart[name];
    }
    calculateTotal();
    displayCart();
}

function updateQuantity(name, change) {
    if (cart[name]) {
        cart[name].quantity += change;
        if (cart[name].quantity <= 0) {
            delete cart[name];
        }
    }
    calculateTotal();
    displayCart();
}

function calculateTotal() {
    total = 0;
    for (let item in cart) {
        total += cart[item].price * cart[item].quantity;
    }
}

function displayCart() {
    const cartContainer = document.getElementById('cartItems');
    cartContainer.innerHTML = '';
    for (let item in cart) {
        cartContainer.innerHTML += `
            <div class="cart-item">
                <span>${item}</span>
                <span>₹${cart[item].price} x ${cart[item].quantity}</span>
                <button onclick="updateQuantity('${item}', 1)" style="background:#6b3e09; color:white; border:none; padding:5px 10px; border-radius:6px; cursor:pointer;">Add</button>
                <button onclick="updateQuantity('${item}', -1)" style="background:#b02a2a; color:white; border:none; padding:5px 10px; border-radius:6px; cursor:pointer;">Remove</button>
            </div>
        `;
    }
    document.getElementById('totalAmount').textContent = total.toFixed(2);
}

// ✅ Checkout - send cart & total to PHP
function checkout() {
    if (Object.keys(cart).length === 0) {
        alert('Your cart is empty!');
        return;
    }

    fetch('checkout.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `cart=${encodeURIComponent(JSON.stringify(cart))}&total=${total}`
    })
    .then(response => response.text())
    .then(data => {
        window.location.href = data; // PHP sends thankyou.php?order_id=xx
    });
}
</script>

</body>
</html>
