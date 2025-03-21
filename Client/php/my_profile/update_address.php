<?php 
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";

$id = (int) check_input($_POST['id']);
$old_address = check_input($_POST['old_address']);
$address = check_input($_POST['address']);


$sql = "
update delivery_addresses
set address = '$address'
where customer_id = $id and address = '$old_address';
";

if($conn->query($sql)) {
    header("location: ../../my_profile.php?address=true&success=true");
}

?>