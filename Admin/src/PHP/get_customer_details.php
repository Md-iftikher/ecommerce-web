<?php
header('Content-Type: application/json');

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "ecommerce");

if (!$conn) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

// Get customer ID from query
if (!isset($_GET['id'])) {
    echo json_encode(["success" => false, "message" => "Customer ID is required"]);
    exit;
}

$customer_id = intval($_GET['id']);

// Get customer info
$customer_sql = "SELECT * FROM customers WHERE customer_id = $customer_id";
$customer_result = mysqli_query($conn, $customer_sql);

if (!$customer_result || mysqli_num_rows($customer_result) == 0) {
    echo json_encode(["success" => false, "message" => "Customer not found"]);
    exit;
}

$customer = mysqli_fetch_assoc($customer_result);

// Get delivery addresses
$addresses = [];
$address_sql = "SELECT address FROM delivery_addresses WHERE customer_id = $customer_id";
$address_result = mysqli_query($conn, $address_sql);
if ($address_result && mysqli_num_rows($address_result) > 0) {
    while ($row = mysqli_fetch_assoc($address_result)) {
        $addresses[] = $row['address'];
    }
}

// Get total orders
$order_count = 0;
$order_count_sql = "SELECT COUNT(*) AS total_orders FROM orders WHERE customer_id = $customer_id";
$order_count_result = mysqli_query($conn, $order_count_sql);
if ($order_count_result) {
    $row = mysqli_fetch_assoc($order_count_result);
    $order_count = $row['total_orders'];
}

// Get orders list
$orders = [];
$order_sql = "SELECT order_id, total_price, status, created_at FROM orders WHERE customer_id = $customer_id ORDER BY created_at DESC";
$order_result = mysqli_query($conn, $order_sql);
if ($order_result && mysqli_num_rows($order_result) > 0) {
    while ($row = mysqli_fetch_assoc($order_result)) {
        $orders[] = $row;
    }
}

// Final JSON response
echo json_encode([
    "success" => true,
    "customer" => $customer,
    "addresses" => $addresses,
    "total_orders" => $order_count,
    "orders" => $orders
]);

mysqli_close($conn);
