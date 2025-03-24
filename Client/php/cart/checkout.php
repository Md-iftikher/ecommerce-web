<?php
session_start();
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";


if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['error' => 'You must be logged in to checkout']);
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Get active cart
$sql = "SELECT cart_id FROM carts WHERE customer_id = ? AND status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $cart = $result->fetch_assoc();
    $cart_id = $cart['cart_id'];

    // Get cart items
    $sql = "SELECT product_id, quantity, price FROM cart_items WHERE cart_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);

    if (empty($cart_items)) {
        echo json_encode(['error' => 'Cart is empty']);
        exit;
    }

    // Calculate total price
    $total_price = 0;
    foreach ($cart_items as $item) {
        $total_price += $item['quantity'] * $item['price'];
    }

    // Create order
    $sql = "INSERT INTO orders (customer_id, total_price, status) VALUES (?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $customer_id, $total_price);
    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;

        // Move cart items to order_items
        foreach ($cart_items as $item) {
            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }

        // Mark cart as completed
        $sql = "UPDATE carts SET status = 'completed' WHERE cart_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();

        echo json_encode(['success' => 'Order placed successfully']);
    } else {
        echo json_encode(['error' => 'Failed to create order']);
    }
} else {
    echo json_encode(['error' => 'No active cart found']);
}
?>
