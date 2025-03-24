<?php
session_start();
include_once __DIR__ . "/../php/config.php";

if (!isset($_SESSION['customer_id'])) {
    header('Location: ../Pages/login.php');
    exit;
}

$customer_id = $_SESSION['customer_id'];

$sql = "
    SELECT ci.product_id, ci.quantity, ci.price, p.product_name, p.image_url 
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.product_id
    JOIN carts c ON ci.cart_id = c.cart_id
    WHERE c.customer_id = $customer_id AND c.status = 'active';
";
$result = $conn->query($sql);
$cart_items = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <link href="../Styles/style.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div id="nav-container"></div>

    <main class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-center mb-8">Your Cart</h2>
        <div class="grid grid-cols-1 gap-6">
            <?php if (empty($cart_items)): ?>
                <p class="text-center text-gray-600">Your cart is empty.</p>
            <?php else: ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <img src="<?= $item['image_url'] ?>" alt="<?= $item['product_name'] ?>" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h2 class="text-lg font-semibold"><?= $item['product_name'] ?></h2>
                            <p class="text-blue-600 font-bold">$<?= number_format($item['price'], 2) ?></p>
                            <p class="text-gray-600 text-sm mt-2">Quantity: <?= $item['quantity'] ?></p>
                            <button onclick="removeFromCart(<?= $item['product_id'] ?>)" class="mt-4 w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-800 transition-all">Remove</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="mt-8 text-center">
            <button onclick="checkout()" class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-800 transition-all">Buy Now</button>
        </div>
    </main>

    <div id="footer-container"></div>
    <script src="../js/cart.js"></script>
    <!-- <script src="../js/home.js"></script> -->
</body>
</html>