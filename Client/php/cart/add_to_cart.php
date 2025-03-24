<?php
session_start();
include_once __DIR__ . "/../config.php";

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['error' => 'You must be logged in to add items to the cart']);
    exit;
}

$customer_id = $_SESSION['customer_id'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];

$sql = "SELECT cart_id FROM carts WHERE customer_id = $customer_id AND status = 'active'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $cart = $result->fetch_assoc();
    $cart_id = $cart['cart_id'];
} else {
    $sql = "INSERT INTO carts (customer_id, status) VALUES ($customer_id, 'active')";
    if ($conn->query($sql)) {
        $cart_id = $conn->insert_id;
    } else {
        echo json_encode(['error' => 'Failed to create a new cart']);
        exit;
    }
}

$sql = "INSERT INTO cart_items (cart_id, product_id, quantity, price) VALUES ($cart_id, $product_id, $quantity, $price)";
if ($conn->query($sql)) {
    echo json_encode(['success' => 'Product added to cart']);
} else {
    echo json_encode(['error' => 'Failed to add product to cart']);
}
?>