<?php
session_start();
session_destroy();
header("Location: ../front_login_pages/loginpage.html");
exit();
?>
