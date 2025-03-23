<?php
// session_start();
$customer_id = (int)$_SESSION['customer_id'];

include_once __DIR__ . "/../config.php";

$fname_query = "
select * from customers 
where customer_id = $customer_id; 
";

$fname_result = $conn->query($fname_query);
$fname = $fname_result->fetch_assoc()['first_name'];


$sql = "
select * from delivery_addresses
where customer_id = $customer_id;
";

$result = $conn->query($sql);

?>