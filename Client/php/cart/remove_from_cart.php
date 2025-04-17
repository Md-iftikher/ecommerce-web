<?php
session_start();
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";


if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['error' => 'You must be logged in to remove items from the cart']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$product_id = $data['product_id'] ?? null;
$customer_id = $_SESSION['customer_id'];

if (!$product_id) {
    echo json_encode(['error' => 'Product ID is required']);
    exit;
}

// Get the active cart ID
$sql = "SELECT cart_id FROM carts WHERE customer_id = ? AND status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $cart = $result->fetch_assoc();
    $cart_id = $cart['cart_id'];

    // Remove product from cart
    $sql = "DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cart_id, $product_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Product removed from cart']);
    } else {
        echo json_encode(['error' => 'Failed to remove product']);
    }
} else {
    echo json_encode(['error' => 'No active cart found']);
}
?>
