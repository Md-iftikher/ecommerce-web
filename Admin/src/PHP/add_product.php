<?php


include_once __DIR__ . "/config.php";

header('Content-Type: application/json');

// Get and validate input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (empty($data) || json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
    exit();
}

// Validate required fields
if (empty($data['product_name']) || !isset($data['price']) || !isset($data['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Product name, price and quantity are required']);
    exit();
}

try {
    // Prepare the query with all fields including timestamps
    $query = "INSERT INTO products (
                product_name, 
                description, 
                price, 
                quantity, 
                image_url, 
                category_id,
                created_at,
                updated_at
              ) VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Handle NULL values for optional fields
    $description = !empty($data['description']) ? $data['description'] : null;
    $image_url = !empty($data['image_url']) ? $data['image_url'] : null;
    $category_id = !empty($data['category_id']) ? $data['category_id'] : null;

    // Validate price and quantity
    if (!is_numeric($data['price']) || $data['price'] < 0) {
        throw new Exception("Price must be a positive number");
    }
    
    if (!is_numeric($data['quantity']) || $data['quantity'] < 0) {
        throw new Exception("Quantity must be a non-negative integer");
    }

    // Bind parameters - note the 'd' for decimal price
    $bound = $stmt->bind_param(
        'ssdiss', // types: string, string, double, integer, string, integer
        $data['product_name'],
        $description,
        $data['price'],
        $data['quantity'],
        $image_url,
        $category_id
    );

    if (!$bound) {
        throw new Exception("Parameter binding failed: " . $stmt->error);
    }

    // Execute the query
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    // Return success with the new product ID
    echo json_encode([
        'success' => true,
        'product_id' => $stmt->insert_id,
        'message' => 'Product added successfully'
    ]);

} catch (Exception $e) {
    error_log("Product add error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error adding product: ' . $e->getMessage()
    ]);
}
?>