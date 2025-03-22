<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";


$first_name = check_input($_POST['first-name']);
$last_name = check_input($_POST['last-name']);
$email = check_input($_POST['email']);
$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$contact = check_input($_POST['contact']);
$dob = check_input($_POST['dob']);
$gender = check_input($_POST['gender']);

if(email_exists($email)){
    header("location: ../../Pages/signup.php?account_exists=true");
    exit();
}

$sql = "
insert into customers(first_name, last_name, email, hashed_password, contact, dob, gender)
values (
'$first_name', '$last_name', '$email', '$hashed_password', '$contact', '$dob', '$gender');
";

if($conn->query($sql)){
    header("location: ../../index.php?signed_up=true");
    exit();
}

?>