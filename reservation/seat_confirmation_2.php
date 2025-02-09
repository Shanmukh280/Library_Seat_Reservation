<?php

session_start();

// Include database connection
include('db_connect.php'); 

$seat_number = $_GET['seat_number'] ?? 'N/A';
$student_id = $_GET['s_id'] ?? 'N/A';

// Retrieve seat number and student ID from URL parameters
// $seat_number = $_SESSION['seat_number'];
// $student_id = $_SESSION['s_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <!-- <link rel="stylesheet" href="mystyle2.css">  -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #00202A;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }
        .confirmation-box {
            background-color: #00202A;
            color: white;
            border-radius: 15px;
            padding: 50px 30px;
            width: 400px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .confirmation-box h2 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            animation: floatText 3s ease-in-out infinite alternate;
        }

        .confirmation-box p {
            font-size: 1.2rem;
            margin: 15px 0;
            opacity: 0.8;
        }

        .confirmation-box .info {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 15px 0;
            color: #ffeb3b; /* Yellow color for emphasis */
            animation: floatText 2s ease-in-out infinite alternate;
        }

        .confirmation-box .time {
            font-size: 1.2rem;
            color: #77295d; /* Red color for the booking time */
            margin-top: 25px;
            animation: floatText 2.5s ease-in-out infinite alternate;
        }

        .confirmation-box .btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 30px;
            width: 100%;
        }

        .confirmation-box .btn:hover {
            background-color: #45a049;
        }

        .confirmation-box .footer {
            font-size: 0.9rem;
            margin-top: 30px;
            color: #777;
        }

        /* Print button style */
        .confirmation-box .print-btn {
            background-color: #2196F3; /* Blue color for print button */
            margin-top: 20px;
        }

        .confirmation-box .print-btn:hover {
            background-color: #1e88e5;
        }

        /* Floating text animation */
        @keyframes floatText {
            0% {
                transform: translateY(-5px);
                opacity: 0.8;
            }
            100% {
                transform: translateY(5px);
                opacity: 1;
            }
        }

    </style>
</head>
<body>
    <div class="confirmation-box">
        <h2>Booking Confirmed!</h2>
        <p>Thank you for reserving your seat.</p>
        
        <div class="info">
            <p>Seat Number: <span id="seatNumber"><?php echo $seat_number; ?></span></p>
            <p>Student ID: <span id="studentId"><?php echo $student_id; ?></span></p>
        </div>

        <div class="time">
            <p>Booked on: <span id="bookingTime"></span></p>
        </div>

        <button class="btn" id="goBackBtn">Go Back</button>
        <button class="btn print-btn" id="printBtn">Print Confirmation</button>

        <div class="footer">
            &copy; 2025 Seat Reservation System
        </div>
    </div>

    <script>
        // Display current time on confirmation
        document.getElementById('bookingTime').textContent = new Date().toLocaleString();

        // Function to handle the 'Go Back' button
        document.getElementById('goBackBtn').addEventListener('click', () => {
            window.location.href = 'seat_reservation.html';
        });

        // Function to handle the 'Print Confirmation' button
        document.getElementById('printBtn').addEventListener('click', () => {
            window.print();
        });
    </script>
</body>
</html>
