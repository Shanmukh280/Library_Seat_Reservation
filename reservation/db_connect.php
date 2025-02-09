<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "library_seat_reservation";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
