<?php
include_once __DIR__ . "/config.php";

header('Content-Type: application/json');

try {
    $search = $_GET['search'] ?? '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    if (empty($search)) {
        throw new Exception('Search term is required');
    }

    $searchTerm = "%$search%";
    $query = "SELECT p.*, c.category_name FROM products p
              LEFT JOIN categories c ON p.category_id = c.category_id
              WHERE p.product_name LIKE ? OR p.description LIKE ?";
    
    // Count total
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM ($query) AS counted");
    $countStmt->bind_param('ss', $searchTerm, $searchTerm);
    $countStmt->execute();
    $total = $countStmt->get_result()->fetch_assoc()['total'];

    // Get results
    $query .= " LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssii', $searchTerm, $searchTerm, $limit, $offset);
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