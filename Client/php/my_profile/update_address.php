<?php 
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";

$id = (int) check_input($_POST['id']);
$old_address = check_input($_POST['old_address']);
$address = check_input($_POST['address']);


$sql = "
update delivery_addresses
set address = ?
where customer_id = ? and address = ?;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sis", $address, $id, $old_address);


if($stmt->execute()) {
    $stmt->close();
    header("location: ../../Pages/my_profile.php?address=true&success=true");
    exit();
}

?>