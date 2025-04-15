<?php
session_start();
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Include database configuration
require_once __DIR__ . '/config.php';

try {
    // Verify database connection
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    $data = [];

    // 1. Total Orders
    $stmt = $conn->prepare("SELECT COUNT(*) AS totalOrders FROM orders");
    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }
    if (!$stmt->execute()) {
        throw new Exception("Execute statement failed: " . $stmt->error);
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $data['totalOrders'] = $row ? (int)$row['totalOrders'] : 0;
    $stmt->close();

    // 2. Pending Orders
    $stmt = $conn->prepare("SELECT COUNT(*) AS pendingOrders FROM orders WHERE status = ?");
    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }
    $status = 'Pending';
    $stmt->bind_param('s', $status);
    if (!$stmt->execute()) {
        throw new Exception("Execute statement failed: " . $stmt->error);
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $data['pendingOrders'] = $row ? (int)$row['pendingOrders'] : 0;
    $stmt->close();

    // 3. Total Revenue (only from completed orders)
    $stmt = $conn->prepare("SELECT COALESCE(SUM(total_amount), 0) AS totalRevenue FROM orders WHERE status = ?");
    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }
    $status = 'Completed';
    $stmt->bind_param('s', $status);
    if (!$stmt->execute()) {
        throw new Exception("Execute statement failed: " . $stmt->error);
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $data['totalRevenue'] = $row ? (float)$row['totalRevenue'] : 0.00;
    $stmt->close();

    // 4. New Customers (last 7 days)
    $stmt = $conn->prepare("SELECT COUNT(*) AS newCustomers FROM customers WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }
    if (!$stmt->execute()) {
        throw new Exception("Execute statement failed: " . $stmt->error);
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $data['newCustomers'] = $row ? (int)$row['newCustomers'] : 0;
    $stmt->close();

    // 5. Recent Orders (last 5)
    $stmt = $conn->prepare("
        SELECT
            o.order_id,
            CONCAT(c.first_name, ' ', c.last_name) AS customer,
            DATE_FORMAT(o.order_date, '%Y-%m-%d') AS date,
            o.total_amount AS amount,
            o.status
        FROM orders o
        JOIN customers c ON o.customer_id = c.customer_id
        ORDER BY o.order_date DESC
        LIMIT 5
    ");
    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }
    if (!$stmt->execute()) {
        throw new Exception("Execute statement failed: " . $stmt->error);
    }
    $result = $stmt->get_result();
    $data['recentOrders'] = [];
    while ($row = $result->fetch_assoc()) {
        $data['recentOrders'][] = [
            'order_id' => $row['order_id'],
            'customer' => $row['customer'],
            'date' => $row['date'],
            'amount' => (float)$row['amount'],
            'status' => $row['status']
        ];
    }
    $stmt->close();

    // Return successful response
    echo json_encode([
        'success' => true,
        'data' => $data
    ]);

} catch (Exception $e) {
    // Log error
    error_log("Dashboard Error: " . $e->getMessage());

    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error',
        'message' => $e->getMessage()
    ]);

    // Ensure connection is closed in case of error
    if (isset($conn) && $conn) {
        $conn->close();
    }
}
?>