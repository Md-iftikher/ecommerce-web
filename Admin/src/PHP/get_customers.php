<?php
header('Content-Type: application/json');

$conn = mysqli_connect("localhost", "root", "", "ecommerce");

if (!$conn) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$sql = "
    SELECT 
        c.customer_id,
        c.first_name,
        c.last_name,
        c.email,
        c.contact,
        c.gender,
        (SELECT COUNT(*) FROM orders o WHERE o.customer_id = c.customer_id) AS total_orders,
        (SELECT address FROM delivery_addresses d WHERE d.customer_id = c.customer_id LIMIT 1) AS address
    FROM customers c
";
$result = mysqli_query($conn, $sql);

$customers = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $customers[] = $row;
    }

    echo json_encode([
        "success" => true,
        "customers" => $customers
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Query failed: " . mysqli_error($conn)
    ]);
}

mysqli_close($conn);
