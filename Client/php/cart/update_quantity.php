<?php
session_start();
include_once __DIR__ . "/../config.php";

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['error' => 'Please login to update cart']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['product_id'] ?? null;
$newQuantity = $data['quantity'] ?? 1;

// Validate input
if (!$productId || !is_numeric($newQuantity) || $newQuantity < 1) {
    echo json_encode(['error' => 'Invalid quantity']);
    exit;
}

// Get cart ID
$stmt = $conn->prepare("SELECT cart_id FROM carts WHERE customer_id = ? AND status = 'active'");
$stmt->bind_param("i", $_SESSION['customer_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'No active cart found']);
    exit;
}

$cart = $result->fetch_assoc();
$cartId = $cart['cart_id'];

// Update quantity
$stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_id = ? AND product_id = ?");
$stmt->bind_param("iii", $newQuantity, $cartId, $productId);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    echo json_encode(['error' => 'Product not found in cart']);
    exit;
}

echo json_encode(['success' => true]);
?>