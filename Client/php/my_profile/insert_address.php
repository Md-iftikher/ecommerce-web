<?php 
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";


$id = (int) check_input($_POST['id']);
$address = check_input($_POST['address']);


$sql = "
insert into delivery_address
values ( $id, '$address');
";

if($conn->query($sql)) {
    header("location: ../../my_profile.php?address=true&success=true");
}

?>