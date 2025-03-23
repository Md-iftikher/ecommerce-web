<?php 
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";

$data = json_decode(file_get_contents("php://input"), true);
$email = check_input($data['email']);

$sql = "
select email 
from customers
where email = '$email';
";
$result = $conn->query($sql);

$response = [
    'email_exists' => false
];

if($result->fetch_assoc()) {
    $response['email_exists'] = true;
}

echo json_encode($response);


?>