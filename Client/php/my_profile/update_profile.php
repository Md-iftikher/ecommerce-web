<?php
session_start();
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";

$id = (int) check_input($_POST['id']);
$fname = check_input($_POST['fname']);
$lname = check_input($_POST['lname']);
$email = check_input($_POST['email']);
$contact = check_input($_POST['contact']);
$dob = check_input($_POST['dob']);
$gender = check_input($_POST['gender']);

$sql = "
update customers
set first_name = '$fname', last_name = '$lname', email = '$email', contact = '$contact', dob = '$dob', gender = '$gender'
where customer_id = $id;
";

if($conn->query($sql)) {
    $_SESSION['first_name'] = $fname;
    header("location: ../../Pages/my_profile.php?profile=true&success=true");
}

?>