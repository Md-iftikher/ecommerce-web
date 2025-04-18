<?php
include_once __DIR__ . "/config.php";

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Product ID not provided']);
    exit;
}

$productId = (int)$_GET['id'];

try {
    $query = "
        SELECT
            p.*,
            c.category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        WHERE p.product_id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }

    $product = $result->fetch_assoc();
    echo json_encode(['success' => true, 'product' => $product]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>