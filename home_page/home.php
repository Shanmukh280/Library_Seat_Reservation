<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../front_login_pages/loginpage.html");
    exit();
}

// Include database connection
include('db_connect.php'); 


// Fetch user details
$student_id = $_SESSION['student_id'];
$query = "SELECT * FROM student_details WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// If user not found, destroy session and redirect
if (!$user) {
    session_destroy();
    header("Location: ../front_login_pages/loginpage.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #00212A;
            color: white;
            margin: 0;
            padding: 0;
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .profile-btn, .scan-btn {
            padding: 10px 20px;
            background-color: white;
            color: #00212A;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .profile-btn:hover, .scan-btn:hover {
            background-color: #0097a7;
            color: white;
        }
        .scanner-container {
            display: flex;
            align-items: center;
        }
        .scanner-icon {
            width: 24px;
            height: 24px;
            background-image: url('path_to_your_scanner_icon.png');
            background-size: cover;
            margin-right: 10px;
        }
        .cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }
        .card {
            width: 200px;
            height: 200px;
            margin: 15px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .quote-container {
            text-align: center;
            padding: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <button class="profile-btn" id="profileButton" onclick="openProfile()">Profile</button>

        <div class="scanner-container">
            <button class="scan-btn" onclick="scanQRCode()">
                <div class="scanner-icon"></div>
                Scan Here
            </button>
            <input type="file" id="cameraInput" accept="image/*;capture=camera" style="display: none;">
        </div>
    </div>

    <section class="cards">
        <div class="card about" onclick="navigateTo('../about/aboutpage.html')">
            <h2>About Library</h2>
        </div>
        <div class="card seat-availability" onclick="navigateTo('seat_availability.php')">
            <h2>Seat Availability</h2>
        </div>
        <div class="card seat-booking" onclick="navigateTo('../reservation/seat_reservation_2.php')">
            <h2>Seat Booking</h2>
        </div>
        <div class="card books-available" onclick="navigateTo('../about/book.html')">
            <h2>Books Available</h2>
        </div>
    </section>

    <div class="quote-container">
        <p id="quote">"A library is not a luxury but one of the necessities of life." – Henry Ward Beecher</p>
    </div>
    <script>
        function openProfile() {
            window.location.href = 'profile.php';
        }

        function scanQRCode() {
            // Implement QR code scanning functionality
        }

        function navigateTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
