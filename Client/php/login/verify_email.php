<?php 
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";

$data = json_decode(file_get_contents("php://input"), true);
$email = check_input($data['email']);

$sql = "
SELECT email 
FROM customers
WHERE email = ?;
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

$response = [
    'email_exists' => false
];


if ($result->fetch_assoc()) {
    $response['email_exists'] = true;
}

echo json_encode($response);
?>
