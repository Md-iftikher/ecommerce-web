<?php
session_start();
include_once __DIR__ . "/../config.php";

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['error' => 'You must be logged in to remove items from the cart']);
    exit;
}

$customer_id = $_SESSION['customer_id'];
$product_id = $_POST['product_id'];

$sql = "SELECT cart_id FROM carts WHERE customer_id = $customer_id AND status = 'active'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $cart = $result->fetch_assoc();
    $cart_id = $cart['cart_id'];

    $sql = "DELETE FROM cart_items WHERE cart_id = $cart_id AND product_id = $product_id";
    if ($conn->query($sql)) {
        echo json_encode(['success' => 'Product removed from cart']);
    } else {
        echo json_encode(['error' => 'Failed to remove product from cart']);
    }
} else {
    echo json_encode(['error' => 'No active cart found']);
}
?>