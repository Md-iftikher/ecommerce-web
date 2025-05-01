<?php
include_once __DIR__ . "/config.php";

header('Content-Type: application/json');

try {
    $category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    if ($category <= 0) {
        throw new Exception('Invalid category ID');
    }

    // Count total
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM products WHERE category_id = ?");
    $countStmt->bind_param('i', $category);
    $countStmt->execute();
    $total = $countStmt->get_result()->fetch_assoc()['total'];

    // Get results
    $query = "SELECT p.*, c.category_name FROM products p
              LEFT JOIN categories c ON p.category_id = c.category_id
              WHERE p.category_id = ?
              LIMIT ? OFFSET ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iii', $category, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'success' => true,
        'products' => $products,
        'total' => $total,
        'page' => $page,
        'limit' => $limit,
        'category_name' => $products[0]['category_name'] ?? ''
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>