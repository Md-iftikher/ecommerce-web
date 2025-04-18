<?php
include_once __DIR__ . "/config.php";

header('Content-Type: application/json');

try {
    $search = $_GET['search'] ?? '';
    $category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    if (empty($search)) {
        throw new Exception('Search term is required');
    }
    if ($category <= 0) {
        throw new Exception('Invalid category ID');
    }

    $searchTerm = "%$search%";
    $query = "SELECT p.*, c.category_name FROM products p
              LEFT JOIN categories c ON p.category_id = c.category_id
              WHERE (p.product_name LIKE ? OR p.description LIKE ?)
              AND p.category_id = ?";
    
    // Count total
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM ($query) AS counted");
    $countStmt->bind_param('ssi', $searchTerm, $searchTerm, $category);
    $countStmt->execute();
    $total = $countStmt->get_result()->fetch_assoc()['total'];

    // Get results
    $query .= " LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssiii', $searchTerm, $searchTerm, $category, $limit, $offset);
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