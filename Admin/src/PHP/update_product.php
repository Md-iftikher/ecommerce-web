<?php

include_once __DIR__ . "/config.php";

header('Content-Type: application/json');

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Log received data for debugging
error_log("Received update data: " . print_r($data, true));

// Validate input
if (empty($data['product_id']) || empty($data['product_name']) || !isset($data['price']) || !isset($data['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    // Verify product exists first
    $checkStmt = $conn->prepare("SELECT product_id FROM products WHERE product_id = ?");
    $checkStmt->bind_param('i', $data['product_id']);
    $checkStmt->execute();
    $checkStmt->store_result();
    
    if ($checkStmt->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }

    $query = "
        UPDATE products SET
            product_name = ?,
            description = ?,
            price = ?,
            quantity = ?,
            image_url = ?,
            category_id = ?,
            updated_at = CURRENT_TIMESTAMP
        WHERE product_id = ?
    ";
    
    // Convert empty strings to NULL for optional fields
    $description = !empty($data['description']) ? $data['description'] : null;
    $image_url = !empty($data['image_url']) ? $data['image_url'] : null;
    $category_id = !empty($data['category_id']) ? $data['category_id'] : null;
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $bindResult = $stmt->bind_param(
        'ssdissi',
        $data['product_name'],
        $description,
        $data['price'],
        $data['quantity'],
        $image_url,
        $category_id,
        $data['product_id']
    );
    
    if (!$bindResult) {
        throw new Exception("Bind failed: " . $stmt->error);
    }
    
    $executeResult = $stmt->execute();
    
    if (!$executeResult) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Product updated successfully',
        'affected_rows' => $stmt->affected_rows
    ]);
    
} catch (Exception $e) {
    error_log("Update error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error updating product: ' . $e->getMessage()
    ]);
}
?>