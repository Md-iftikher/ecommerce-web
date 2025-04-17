<?php
include_once __DIR__ . "/config.php";

try {
    $query = "
        SELECT
            COUNT(*) as all_count,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
            SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing_count,
            SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_count,
            SUM(CASE WHEN status = 'out_for_delivery' THEN 1 ELSE 0 END) as out_for_delivery_count
        FROM orders
    ";

    $result = $conn->query($query);
    $counts = $result->fetch_assoc();

    echo json_encode([
        'success' => true,
        'counts' => [
            'all' => $counts['all_count'],
            'pending' => $counts['pending_count'],
            'processing' => $counts['processing_count'],
            'delivered' => $counts['delivered_count'],
            'out_for_delivery' => $counts['out_for_delivery_count']
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>