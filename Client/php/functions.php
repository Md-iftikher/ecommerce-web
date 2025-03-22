<?php
include_once __DIR__ . "/./config.php";

function check_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


function no_of_addresses() {
  global $conn;

  $sql = "
  select count(address) as addressNo from delivery_addresses
  where customer_id = 1;
  ";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  return $row['addressNo'];

}

function email_exists($email) {
  global $conn;

  $sql = "
  select email 
  from customers
  where email = '$email';
  ";
  $result = $conn->query($sql);
  if($result->fetch_assoc()) {
    return true;
  }
  else return false;
}

?>