<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";


$email = check_input($_POST['email']);
$password = $_POST['password'];


$sql = "
select first_name, hashed_password, customer_id from customers
where email = '$email';
";

$result = $conn->query($sql);
$row = $result->fetch_assoc();
$hash = $row['hashed_password'];
$customer_id = $row['customer_id'];
$first_name = $row['first_name'];


if(password_verify($password, $hash)) {
    session_start();
    $_SESSION['customer_id'] = $customer_id;
    $_SESSION['first_name'] = $first_name;
    header("location: ../../index.php");
    exit();
}
else {
    header("location: ../../Pages/login.php?success=true");
    exit();
}



?>