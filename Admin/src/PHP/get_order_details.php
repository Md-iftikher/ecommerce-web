<?php
include_once __DIR__ . "/config.php";

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Order ID not provided']);
    exit;
}

$orderId = (int)$_GET['id'];

try {
    // Get order details
    $orderQuery = "
        SELECT 
            o.*,
            CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
            c.email AS customer_email,
            da.address
        FROM orders o
        JOIN customers c ON o.customer_id = c.customer_id
        JOIN delivery_addresses da ON o.address_id = da.address_id
        WHERE o.order_id = ?
    ";
    $stmt = $conn->prepare($orderQuery);
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();

    // Get order items
    $itemsQuery = "
        SELECT 
            oi.*,
            p.product_name,
            p.image_url
        FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        WHERE oi.order_id = ?
    ";
    $stmt = $conn->prepare($itemsQuery);
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'success' => true,
        'order' => $order,
        'items' => $items
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
