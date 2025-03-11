<?php 
include __DIR__ . "/../config.php";

$id = (int) $_POST['id'];
$old_address = $_POST['old_address'];
$address = $_POST['address'];


$sql = "
update delivery_address
set address = '$address'
where cust_id = $id and address = '$old_address';
";

if($conn->query($sql)) {
    header("location: ../../my_profile.php?address=true&success=true");
}

?>