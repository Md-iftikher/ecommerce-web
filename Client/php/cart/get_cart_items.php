<?php

session_start();
include_once __DIR__ . "/../config.php";

if (!isset($_SESSION["customer_id"])) {
    echo json_encode(["error" => "you must be logged in to view your cart"]);
    exit();
}

$customer_id = $_SESSION["customer_id"];

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

echo json_encode($cart_items);
