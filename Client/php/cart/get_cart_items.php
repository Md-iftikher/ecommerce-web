<?php
session_start();
include_once __DIR__ . "/../config.php";

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['error' => 'You must be logged in to view cart items']);
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Fetch cart items
$sql = "SELECT ci.product_id, p.product_name, p.image_url, ci.quantity, ci.price
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.product_id
        WHERE ci.cart_id = (SELECT cart_id FROM carts WHERE customer_id = ? AND status = 'active')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}

echo json_encode($cart_items);
?>
