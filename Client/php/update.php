<?php 
include "config.php";
$id = (int) $_POST['id'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$contact = $_POST['contact'];
$dob_day = $_POST['dob_day'];
$dob_month = $_POST['dob_month'];
$dob_year = $_POST['dob_year'];
$dob = "$dob_year-$dob_month-$dob_day";
$gender = $_POST['gender'];

$sql = "
update customer
set first_name = '$fname', last_name = '$lname', email = '$email', contact = '$contact', dob = $dob, gender = '$gender';
where customer_id = $id;
"
?>