<?php
if(isset($_SESSION['customer_id'])) {
  $customer_id = (int)$_SESSION['customer_id'];
}


include_once __DIR__ . "/./config.php";

function check_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


function no_of_addresses() {
  global $conn;
  global $customer_id;

  $sql = "
  select count(address) as addressNo from delivery_addresses
  where customer_id = ?;
  ";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $customer_id);
  $stmt->execute();
  
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  return $row['addressNo'];

}

function email_exists($email) {
  global $conn;

  $sql = "
  select email 
  from customers
  where email = ?;
  ";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  
  $result = $stmt->get_result();
  
  if($result->fetch_assoc()) {
    return true;
  }
  else return false;
}

?>