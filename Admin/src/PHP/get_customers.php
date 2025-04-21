<?php
header('Content-Type: application/json');

$conn = mysqli_connect("localhost", "root", "", "ecommerce");

if (!$conn) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

// Get pagination parameters from GET request, default if not set
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10; // Set your desired number of customers per page

// Calculate the offset
$offset = ($page - 1) * $limit;

// SQL query to get customers with pagination
// Also includes total orders and one address using subqueries
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
    LIMIT $limit OFFSET $offset
";

$result = mysqli_query($conn, $sql);

$customers = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $customers[] = $row;
    }

    // SQL query to get the total count of all customers (for pagination info)
    // Removed the WHERE clause for search
    $totalSql = "
        SELECT COUNT(*) AS total
        FROM customers c
    ";
    $totalResult = mysqli_query($conn, $totalSql);
    $totalRow = mysqli_fetch_assoc($totalResult);
    $totalCustomers = $totalRow['total'];

    echo json_encode([
        "success" => true,
        "customers" => $customers,
        "total" => intval($totalCustomers), // Return total as integer
        "limit" => intval($limit),         // Return limit as integer
        "page" => intval($page)            // Return current page as integer
    ]);

    mysqli_free_result($result);
    mysqli_free_result($totalResult);

} else {
    echo json_encode([
        "success" => false,
        "message" => "Query failed: " . mysqli_error($conn)
    ]);
}

mysqli_close($conn);
?>