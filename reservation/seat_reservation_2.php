<?php
session_start();
include 'db_connect.php';

date_default_timezone_set('Asia/Kolkata'); // Set timezone
$currentHour = date('H');
$slotColumn = 'slot_' . ($currentHour - 9 + 1); // Determine the correct slot based on time

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_SESSION['student_id']; // Get student ID from session
    $s_id = $_POST['s_id'];
    $gender = $_POST['gender']; // Get student gender from session
    $seat_id = $_POST['seat_number'];

    if (!$student_id || !$gender || !$seat_id) {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
        exit;
    }

// ✅ Step 1: Validate Student ID and Gender in `student_details` Table
$validate_student_query = "SELECT gender FROM student_details WHERE student_id = ?";
$stmt = $conn->prepare($validate_student_query);
$stmt->bind_param("s", $s_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Invalid Student ID!')</script>";
    exit();
} else {
    $student_data = $result->fetch_assoc();
    if ($student_data['gender'] !== $gender) {
        echo "<script>alert('Incorrect gender for the given Student ID!'); window.location.href='seat_reservation.html';</script>";
        exit();
    }
}

    // Check if the student has already booked another seat in the same slot
    $check_existing_booking = "SELECT seat_id FROM library_time_slots WHERE $slotColumn = ?";
    $stmt2 = $conn->prepare($check_existing_booking);
    $stmt2->bind_param("s", $s_id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if ($result2->num_rows > 0) {
        $seatData2 = $result2->fetch_assoc();
        echo "<script>alert('You have already booked a seat number {$seatData2['seat_id']} in this time slot!');</script>";
        exit();
    }

    // Get seat details
    $seatQuery = "SELECT gender, $slotColumn FROM library_time_slots WHERE seat_id = ?";
    $stmt = $conn->prepare($seatQuery);
    $stmt->bind_param("i", $seat_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $seatData = $result->fetch_assoc();
        if ($seatData['gender'] !== 'Both' && $seatData['gender'] !== $gender) {
            echo json_encode(["status" => "error", "message" => "Seat is restricted to another gender"]);
            exit;
        }
        if (!is_null($seatData[$slotColumn])) {
            echo json_encode(["status" => "error", "message" => "Seat already occupied"]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Seat not found"]);
        exit;
    }

    // Update the table with the student_id in the correct slot column
    $updateQuery = "UPDATE library_time_slots SET $slotColumn = ? WHERE seat_id = ?";
    $stmt = $conn->prepare($updateQuery);
    // $stmt->bind_param("ii", $student_id, $seat_id);
    $stmt->bind_param("si", $s_id, $seat_id);

    if ($stmt->execute()) {
        // echo json_encode(["status" => "success", "message" => "Seat reserved successfully"]);
        header("Location: seat_confirmation_2.php?seat_number=$seat_id&s_id=$s_id");
        echo "<script>window.location.href='seat_confirmation_2.php';</script>";
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to reserve seat"]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Reservation</title>
    <!-- <link rel="stylesheet" href="mystyle.css"> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #00212A;
            display: flex;
        }
        .sidebar {
            width: 150px;
            padding: 20px;
            background-color: #00212A;
            color:white;
        }
        .sidebar select {
            width: 100%;
            padding: 10px 15px;
            font-size: 1rem;
            background-color: #ffffff;
            border: 2px solid #007BFF;
            border-radius: 5px;
            outline: none;
            cursor: pointer;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .sidebar select:focus {
            border-color: #007BFF;
            box-shadow: 0 0 5px rgba(72, 191, 98, 0.5);
        }
        /* Form Styling */
.sidebar form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* Input Fields */
.sidebar input[type="number"],
.sidebar input[type="text"],
.sidebar select {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    background-color: #fff;
    border: 2px solid #007BFF;
    border-radius: 8px;
    outline: none;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.sidebar input:focus,
.sidebar select:focus {
    border-color: #00D9A5;
    box-shadow: 0 0 8px rgba(0, 217, 165, 0.5);
}

/* Submit Button */
.sidebar button {
    padding: 12px;
    font-size: 1rem;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    font-weight: bold;
}

.sidebar button:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

/* Disabled Button */
.sidebar button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

        .main-content {
            flex: 1;
            padding: 20px;
        }
        .header {
            text-align: center;
            background-color:#106569;
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            margin: 0;
            font-size: 2.5rem;
        }
        .seat-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }
        .section {
            flex: 1;
            text-align: center;
            margin-bottom: 20px;
            border: 2px solid #007BFF;
            border-radius: 5px;
            padding: 10px;
            position: relative;
        }
        .section h2 {
            background-color: #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .seat-grid {
            display: grid;
            gap: 10px;
            justify-content: center;
            background-color: #00212A;
        }
        .boys .seat-grid { grid-template-columns: repeat(3, 1fr); }
        .girls .seat-grid { grid-template-columns: repeat(3, 1fr); }
        .both .seat-grid { grid-template-columns: repeat(4, 1fr); }
        .seat {
            width: 40px;
            height: 40px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .seat.available { background-color: #fff; }
        .seat.booked { background-color: #f44336; cursor: not-allowed; color: white; }
        .seat.selected { background-color: #4CAF50; color: white; }
        .seat:hover { transform: scale(1.1); }
        .confirm-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .confirm-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .confirm-btn:hover:not(:disabled) { background-color: #45a049; }
        .footer { text-align: center; padding: 1rem; background-color: #333; color: white; margin-top: 2rem; font-size: 0.9rem; }

        /* Drinking Water Marker */
        .drinking-water {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: #2196F3; /* Blue color */
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .instructions p {
            margin: 5px 0;
            display: flex;
            align-items: center; /* Vertically centers the text and checkbox */
        }

        .instructions label {
            margin-left: 5px;  /* Reduced the space between checkbox and text */
            font-size: 0.8rem;
        }


.checkbox {
    width: 15px;
    height: 15px;
    border: 2px solid #aa5d5d;
    border-radius: 3px;
    margin-right: 5px; /* Space between the checkbox and label */
    display: inline-block;
}

.checkbox.red { background-color: #f44336; }
.checkbox.green { background-color: #4CAF50; }
.checkbox.gray { background-color: #ccc; }

        /* Instructions box at the bottom left */
        .instructions {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background-color: linear-gradient(to right, #004e4c, #006d68, #003d3b);;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 0.8rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 200px;
        }
        /* Bench Marker */
        .bench-marker {
            width: 50%;
            height: 20px;
            background-color: #6d5c13;
            margin: 10px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <form action="seat_reservation_2.php" method="POST">
        <label for="floor">Select Floor:</label>
        <select id="floor" onchange="updateSeatRange()">
            <option value="1">Floor 1</option>
            <option value="2">Floor 2</option>
            <option value="3">Floor 3</option>
        </select>
        <br><br>
        <label for="seat_number">Enter Seat Number:</label>
        <input type="number" id="seat_number" name="seat_number" min="1" max="100">
        <br><br>
        <label for="s_id">Enter Student id:</label>
        <input type="text" id="s_id" name="s_id" >
        <br><br>
        <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
        </select>
        <br><br>
        <button type="submit"> Submit </button>
        </form>
    </div>
    <div class="main-content">
        <div class="header">
            <h1>Library Seat Reservation</h1>
        </div>
        <div class="seat-container">
            <div class="section boys">
                <h2>Boys Section</h2>
                <div class="seat-grid" id="boysGrid"></div>
                <div class="seat-grid"></div>
            </div>
            <div class="section girls">
                <h2>Girls Section</h2>
                <div class="seat-grid" id="girlsGrid"></div>
                <div class="seat-grid"></div>
            </div>
            <div class="section both">
                <h2>Boys & Girls</h2>
                <div class="seat-grid" id="bothGrid"></div>
                <div class="seat-grid"></div>
            </div>
        </div>
    </div>
    <script>
        function updateSeatRange() {
            let floor = document.getElementById("floor").value;
            let seatInput = document.getElementById("seat_number");
            seatInput.min = (floor - 1) * 100 + 1;
            seatInput.max = floor * 100;
            seatInput.value = seatInput.min;
        }
        function reserveSeat() {
            let seat_number = document.getElementById("seat_number").value;
            fetch("seat_reservation.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "seat_number=" + seat_number
            })
            .then(response => response.json())
            .then(data => alert(data.message));
        }
        function generateSeats(gridId, start, count) {
            const seatGrid = document.getElementById(gridId);
            seatGrid.innerHTML = ''; // Clear the grid before generating new seats

            let seatCount = 0;

            for (let i = start; i < start + count; i++) {
                const seat = document.createElement('div');
                seat.classList.add('seat', 'available');
                seat.textContent = i;
                seat.dataset.index = i;

                // seat.addEventListener('click', () => toggleSeatSelection(seat));

                seatGrid.appendChild(seat);
                seatCount++;

                // Insert a bench after every 3 seats
                if (seatCount % 3 === 0) {
                    const benchMarker = document.createElement('div');
                    benchMarker.classList.add('bench-marker');
                    benchMarker.textContent = 'Bench';
                    seatGrid.appendChild(benchMarker);
                }
                
            }
            fetchBookedSeats(gridId, start, count)
        }

        function fetchBookedSeats(gridId, start, count) {
    fetch('fetch_seats_booked.php')
        .then(response => response.json())
        .then(bookedSeats => {
            document.querySelectorAll('.seat').forEach(seat => {
                let seatNumber = parseInt(seat.textContent);
                
                if (!isNaN(seatNumber) && seatNumber >= start && seatNumber < start + count) {
                    if (bookedSeats.includes(seatNumber)) {
                        seat.classList.add('booked');
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching booked seats:', error));
}


        // function fetchBookedSeats(gridId, start, count) {
        //    fetch('fetch_seats_booked.php')
        //     .then(response => response.json())
        //     .then(bookedSeats => {
        //         document.querySelectorAll('.seat').forEach(seat => {
        //             if(parseInt(seat.textContent) <= start + count && parseInt(seat.textContent) >= start){
        //                 if (bookedSeats.includes(parseInt(seat.textContent))) {
        //                     // seat.classList.remove('available');
        //                     seat.classList.add('booked');
        //                     //seat.style.cursor = 'not-allowed';
        //             }}
        //         });
        //     })
        //     .catch(error => console.error('Error fetching booked seats:', error));
        // }

        floor.addEventListener("change", function () {
            let floor = document.getElementById("floor").value;
            let num = 100 * (floor-1)
            generateSeats('boysGrid', num + 1, 30);
            generateSeats('girlsGrid', num + 31, 30);
            generateSeats('bothGrid', num + 61, 40);
        });
        generateSeats('boysGrid', 1, 30);
        generateSeats('girlsGrid', 31, 30);
        generateSeats('bothGrid', 61, 40);

        updateSeatRange();
    </script>
</body>
</html>
