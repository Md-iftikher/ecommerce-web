<?php
// Start session
session_start();

// Check if admin is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: src/pages/welcome.php');
    exit();
} else {
    // If not logged in, redirect to login page
    header('Location: src/pages/login.php');
    exit();
}
?>