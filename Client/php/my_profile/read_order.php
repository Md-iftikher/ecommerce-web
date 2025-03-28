<?php
$customer_id = (int)$_SESSION['customer_id'];

include_once __DIR__ . "/../config.php";

$sql = "
select o.order_id, o.total_price, o.status, date(o.created_at) as date, a.address
from orders o
inner join delivery_addresses a
on o.address_id = a.address_id 
where o.customer_id = ?;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();

$result = $stmt->get_result();

$stmt->close();






?>