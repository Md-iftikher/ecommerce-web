<?php
session_start();
include_once __DIR__ . "/../config.php";



// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    error_log("User not logged in");
    echo json_encode(['error' => 'You must be logged in to add items to the cart']);
    exit;
}

// Get data from the request body
$data = json_decode(file_get_contents("php://input"), true);
$product_id = $data['product_id'] ?? null;
$quantity = $data['quantity'] ?? 1;
$price = $data['price'] ?? null;
$customer_id = $_SESSION['customer_id']; // Assuming customer_id is stored in session

// Validate the input data
if (!$product_id || !$price || $quantity <= 0) {
    error_log("Invalid product data: product_id=$product_id, price=$price, quantity=$quantity");
    echo json_encode(['error' => 'Invalid product data']);
    exit;
}

// Check if the user has an active cart
$sql = "SELECT cart_id FROM carts WHERE customer_id = ? AND status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $cart = $result->fetch_assoc();
    $cart_id = $cart['cart_id'];
} else {
    // Create a new cart if not found
    $sql = "INSERT INTO carts (customer_id, status) VALUES (?, 'active')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    if ($stmt->execute()) {
        $cart_id = $stmt->insert_id;
    } else {
        error_log("Failed to create cart for customer_id=$customer_id");
        echo json_encode(['error' => 'Failed to create a cart']);
        exit;
    }
}

// Insert or update the product in the cart_items table
$sql = "INSERT INTO cart_items (cart_id, product_id, quantity, price) 
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiid", $cart_id, $product_id, $quantity, $price);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Product added to cart']);
} else {
    error_log("Failed to add product to cart for cart_id=$cart_id, product_id=$product_id");
    echo json_encode(['error' => 'Failed to add product to cart']);
}
?>
