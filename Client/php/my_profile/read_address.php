<?php
include_once __DIR__ . "/../config.php";

$fname_query = "
select * from customers 
where customer_id = 1; 
";

$fname_result = $conn->query($fname_query);
$fname = $fname_result->fetch_assoc()['first_name'];


$sql = "
select * from delivery_addresses
where customer_id = 1;
";

$result = $conn->query($sql);

?>