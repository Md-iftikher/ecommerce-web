<?php
include_once __DIR__ . "/config.php";

// Get parameters
$status = isset($_GET['status']) ? $_GET['status'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

try {
    // Build the query
    $query = "
        SELECT 
            o.order_id,
            CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
            c.email AS customer_email,
            o.created_at AS order_date,
            o.total_price AS total_amount,
            o.status,
            COUNT(oi.product_id) AS item_count
        FROM orders o
        JOIN customers c ON o.customer_id = c.customer_id
        LEFT JOIN order_items oi ON o.order_id = oi.order_id
    ";

    $where = [];
    $params = [];
    $types = '';

    if (!empty($status)) {
        $where[] = "o.status = ?";
        $params[] = $status;
        $types .= 's';
    }

    if (!empty($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }

    $query .= " GROUP BY o.order_id ORDER BY o.created_at DESC LIMIT ? OFFSET ?";
    $params = array_merge($params, [$limit, $offset]);
    $types .= 'ii';

    // Execute query
    $stmt = $conn->prepare($query);
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Get total count
    $countQuery = "SELECT COUNT(*) as total FROM orders";
    if (!empty($status)) {
        $countQuery .= " WHERE status = '$status'";
    }
    $totalResult = $conn->query($countQuery)->fetch_assoc();
    $total = $totalResult['total'];

    echo json_encode([
        'success' => true,
        'orders' => $orders,
        'total' => $total,
        'limit' => $limit,
        'page' => $page
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>