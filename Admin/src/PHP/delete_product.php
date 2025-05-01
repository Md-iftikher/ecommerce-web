<?php
include_once __DIR__ . "/config.php";

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (empty($data['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Product ID not provided']);
    exit;
}

try {
    // Start transaction
    $conn->begin_transaction();
    
    // First delete from order_items to maintain referential integrity
    $deleteItemsStmt = $conn->prepare("DELETE FROM order_items WHERE product_id = ?");
    $deleteItemsStmt->bind_param('i', $data['product_id']);
    $deleteItemsStmt->execute();
    
    // Then delete the product
    $deleteProductStmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $deleteProductStmt->bind_param('i', $data['product_id']);
    $deleteProductStmt->execute();
    
    if ($deleteProductStmt->affected_rows > 0) {
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
    } else {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Product not found or already deleted']);
    }
    
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => 'Error deleting product: ' . $e->getMessage()
    ]);
}
?>