<?php
session_start();
include_once __DIR__ . "/../config.php";

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['error' => 'You must be logged in to checkout']);
    exit;
}

$customer_id = $_SESSION['customer_id'];

$sql = "SELECT cart_id FROM carts WHERE customer_id = $customer_id AND status = 'active'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $cart = $result->fetch_assoc();
    $cart_id = $cart['cart_id'];

    $sql = "SELECT product_id, quantity, price FROM cart_items WHERE cart_id = $cart_id";
    $result = $conn->query($sql);
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);

    $total_price = 0;
    foreach ($cart_items as $item) {
        $total_price += $item['quantity'] * $item['price'];
    }

    $sql = "INSERT INTO orders (customer_id, total_price, status) VALUES ($customer_id, $total_price, 'pending')";
    if ($conn->query($sql)) {
        $order_id = $conn->insert_id;

        foreach ($cart_items as $item) {
            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price']})";
            $conn->query($sql);
        }

        $sql = "UPDATE carts SET status = 'completed' WHERE cart_id = $cart_id";
        $conn->query($sql);

        echo json_encode(['success' => 'Order placed successfully']);
    } else {
        echo json_encode(['error' => 'Failed to create order']);
    }
} else {
    echo json_encode(['error' => 'No active cart found']);
}
?>