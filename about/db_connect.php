<?php

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "library_seat_reservation";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection with the session
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>