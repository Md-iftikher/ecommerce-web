<?php
// session_start();
$customer_id = (int)$_SESSION['customer_id'];

include_once __DIR__ . "/../config.php";

$sql = "
select first_name, last_name, email, contact, dob, gender 
from customers
where customer_id = ?;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$fname = $row['first_name'];
$lname = $row['last_name'];
$email = $row['email'];
$contact = $row['contact'];
$dob = $row['dob'];
$gender = $row['gender'];

$stmt->close();

?>



    
