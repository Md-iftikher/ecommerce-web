<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";

// Clean the input data (optional but good)
$first_name = check_input($_POST['first-name']);
$last_name = check_input($_POST['last-name']);
$email = check_input($_POST['email']);
$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$contact = check_input($_POST['contact']);
$dob = check_input($_POST['dob']);
$gender = check_input($_POST['gender']);

// Prepare the SQL query with placeholders (?)
$sql = "INSERT INTO customers (first_name, last_name, email, hashed_password, contact, dob, gender)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

// Initialize the prepared statement
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    // Handle error if prepare() fails
    die("Prepare failed: " . $conn->error);
}

// Bind the parameters to the placeholders
// 'sssssss' means 7 string inputs: s = string
$stmt->bind_param(
    "sssssss",     // types of each placeholder
    $first_name,
    $last_name,
    $email,
    $hashed_password,
    $contact,
    $dob,
    $gender
);

// Execute the statement
if ($stmt->execute()) {
    header("Location: ../../index.php?signed_up=true");
    exit();
} else {
    // Handle error if execution fails
    echo "Error: " . $stmt->error;
}

// Close the statement
$stmt->close();
?>
