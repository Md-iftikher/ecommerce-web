<?php 
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";

$id = (int) check_input($_POST['id']);
$address = check_input($_POST['old_address']);


$sql = "
delete from delivery_address
where cust_id = $id and address = '$address';
";

if($conn->query($sql)) {
    header("location: ../../my_profile.php?address=true&success=true");
}

?>