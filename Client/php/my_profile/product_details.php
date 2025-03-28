<?php

include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";

header("Content-Type: application/json"); // Ensure JSON response

$data = json_decode(file_get_contents("php://input"), true);
$order_id = $data['order_id'];

// Query to get order details
$sql = "
SELECT p.product_name, o.price, o.quantity, (o.price * o.quantity) AS subtotal	
FROM order_items o 
INNER JOIN products p ON o.product_id = p.product_id
WHERE o.order_id = ?;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);

if ($stmt->execute()) { // Call execute() as a function
    $result = $stmt->get_result();
}

$stmt->close();

// Query to get total price
$total_price_sql = "
SELECT SUM(o.price * o.quantity) AS total_price
FROM order_items o 
WHERE o.order_id = ?;
";

$stmt = $conn->prepare($total_price_sql);
$stmt->bind_param("i", $order_id);

$total_price = 0; // Default value

if ($stmt->execute()) {
    $total_price_result = $stmt->get_result();
    if ($total_price_row = $total_price_result->fetch_assoc()) {
        $total_price = $total_price_row['total_price'];
    }
}

$stmt->close();

// Collect order details
$order_details = [];

while ($row = $result->fetch_assoc()) {
    $order_details[] = $row; // Store multiple rows
}

// Combine both order details and total price into one array
$response = [
    'order_details' => $order_details,
    'total_price' => $total_price
];

// Send JSON response
echo json_encode($response);

?>
