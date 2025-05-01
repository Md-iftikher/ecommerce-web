<?php
include_once __DIR__ . "/config.php";

try {
    $query = "SELECT * FROM categories ORDER BY category_name";
    $result = $conn->query($query);
    $categories = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode([
        'success' => true,
        'categories' => $categories
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching categories: ' . $e->getMessage()
    ]);
}
?>


