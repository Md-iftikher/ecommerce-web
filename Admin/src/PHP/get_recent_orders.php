<?php

include_once __DIR__ . "/config.php";

try {
    $query = "
        SELECT 
            o.order_id, 
            CONCAT(c.first_name, ' ', COALESCE(c.last_name, '')) AS customer_name,
            DATE_FORMAT(o.created_at, '%Y-%m-%d') AS order_date,
            o.total_price AS amount, 
            o.status
        FROM orders o
        JOIN customers c ON o.customer_id = c.customer_id
        ORDER BY o.created_at DESC
        LIMIT 5
    ";
    
    $result = $conn->query($query);
    $orders = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode([
        'success' => true,
        'orders' => $orders
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching recent orders: ' . $e->getMessage()
    ]);
}
?>