<?php
// session_start();
$customer_id = (int)$_SESSION['customer_id'];

include_once __DIR__ . "/../config.php";

$sql = "
select * from delivery_addresses
where customer_id = ?;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();


$result = $stmt->get_result();

$stmt->close();

?>