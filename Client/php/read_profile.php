<?php
include "config.php";

$sql = "
select first_name, last_name, email, contact, dob, gender 
from customer
where customer_id = 1;
";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

$fname = $row['first_name'];
$lname = $row['last_name'];
$email = $row['email'];
$contact = $row['contact'];
$dob = explode("-", $row['dob']);
$dob_year = $dob[0];
$dob_month = $dob[1];
$dob_day = $dob[2];
$gender = $row['gender'];

?>



    
