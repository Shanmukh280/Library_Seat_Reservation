<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../front_login_pages/loginpage.php");
    exit();
}
?>
