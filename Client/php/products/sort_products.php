<?php
include_once __DIR__ . "/../config.php";

$input = json_decode(file_get_contents("php://input"), true);
$sort_by = $input['sort_by'] ?? '';

$sql = "
SELECT p.*, c.category_name
FROM products p
INNER JOIN categories c ON p.category_id = c.category_id
WHERE p.product_name LIKE ? or c.category_name LIKE ?;
";

$stmt = $conn->prepare($sql);
$likePattern = "%" . $pattern . "%";
$stmt->bind_param("ss", $likePattern, $likePattern);
$stmt->execute();

$result = $stmt->get_result();

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode($products);
?>