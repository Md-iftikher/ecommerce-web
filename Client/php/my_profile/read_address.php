<?php
include __DIR__ . "/../config.php";


$sql = "
select * from delivery_address
where cust_id = 1;
";

$result = $conn->query($sql);

?>