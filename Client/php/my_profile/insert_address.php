<?php 
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";


$id = (int) check_input($_POST['id']);
$address = check_input($_POST['address']);


$sql = "
insert into delivery_addresses(customer_id, address)
values ( ?, ?);
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id, $address);

if($stmt->execute()) {
    $stmt->close();
    header("location: ../../Pages/my_profile.php?address=true&success=true");
    exit();
}


?>