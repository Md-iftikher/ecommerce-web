<?php
include_once __DIR__ . "/config.php";

header('Content-Type: application/json');

try {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    // Count total
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM products");
    $countStmt->execute();
    $total = $countStmt->get_result()->fetch_assoc()['total'];

    // Get results
    $query = "SELECT p.*, c.category_name FROM products p
              LEFT JOIN categories c ON p.category_id = c.category_id
              LIMIT ? OFFSET ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'success' => true,
        'products' => $products,
        'total' => $total,
        'page' => $page,
        'limit' => $limit
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>