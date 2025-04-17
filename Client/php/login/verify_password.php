<?php 
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";

$data = json_decode(file_get_contents("php://input"), true);
$email = check_input($data['email']);
$password = $data['password'];


$sql = "
select hashed_password from customers
where email = ?;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();


$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hash = $row['hashed_password'];

    $response = [
        'is_password_correct' => password_verify($password, $hash)
    ];
} 
else {
    $response = [
        'is_password_correct' => false
    ];
}

echo json_encode($response);

?>