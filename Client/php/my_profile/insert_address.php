<?php 
include __DIR__ . "/../config.php";

$id = (int) $_POST['id'];
$address = $_POST['address'];


$sql = "
insert into delivery_address
values ( $id, '$address');
";

if($conn->query($sql)) {
    header("location: ../../my_profile.php?address=true&success=true");
}

?>