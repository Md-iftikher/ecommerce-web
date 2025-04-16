<?php
session_start();

include_once __DIR__ . "/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT admin_id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($admin_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['username'] = $username;
            $_SESSION['admin_name'] = $username;  // Added to store admin name in session
            $_SESSION['admin_logged_in'] = true;

            header("Location: ../pages/welcome.php");
            exit();
        } else {
            header("Location: ../pages/login.php?error=1");
            exit();
        }
    } else {
        header("Location: ../pages/login.php?error=1");
        exit();
    }
}
?>
