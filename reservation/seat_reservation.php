<?php
// header('Content-Type: application/json'); // Ensure JSON response

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_seat_reservation";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database Connection Failed: " . $conn->connect_error]));
}

echo "hello world";

// Ensure the script only runs on a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from POST request
    $seat_number = isset($_POST['seat_number']) ? intval($_POST['seat_number']) : null;
    $student_id = isset($_POST['student_id']) ? intval($_POST['student_id']) : null;
    $floor = isset($_POST['floor']) ? $_POST['floor'] : null;

    // Validate input
    if (!$seat_number || !$student_id || !$floor) {
        echo json_encode(["status" => "error", "message" => "Invalid input!"]);
        exit;
    }

    // Set timezone
    date_default_timezone_set('Asia/Kolkata');
    $current_hour = date('H');

    // Determine the correct slot column based on the current time
    $slot_column = null;
    if ($current_hour >= 9 && $current_hour < 10) {
        $slot_column = 'slot_1';
    } elseif ($current_hour >= 10 && $current_hour < 11) {
        $slot_column = 'slot_2';
    } elseif ($current_hour >= 11 && $current_hour < 12) {
        $slot_column = 'slot_3';
    } elseif ($current_hour >= 12 && $current_hour < 13) {
        $slot_column = 'slot_4';
    } elseif ($current_hour >= 13 && $current_hour < 14) {
        $slot_column = 'slot_5';
    } elseif ($current_hour >= 14 && $current_hour < 15) {
        $slot_column = 'slot_6';
    } elseif ($current_hour >= 15 && $current_hour < 16) {
        $slot_column = 'slot_7';
    } elseif ($current_hour >= 16 && $current_hour < 17) {
        $slot_column = 'slot_8';
    } elseif ($current_hour >= 17 && $current_hour < 18) {
        $slot_column = 'slot_9';
    } elseif ($current_hour >= 18 && $current_hour < 19) {
        $slot_column = 'slot_10';
    }

    // If no valid slot found
    if (!$slot_column) {
        echo json_encode(["status" => "error", "message" => "Invalid booking time. Library opens at 9 AM and closes at 7 PM."]);
        exit;
    }

    // Check if the seat is already booked for this slot
    $sql_check = "SELECT $slot_column FROM library_time_slots WHERE seat_id = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("i", $seat_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && $row[$slot_column] !== null) {
        echo json_encode(["status" => "error", "message" => "Seat already booked for this time slot."]);
    } else {
        // Book the seat by inserting student_id into the correct slot column
        $sql_book = "UPDATE library_time_slots SET $slot_column = ? WHERE seat_id = ?";
        $stmt = $conn->prepare($sql_book);
        $stmt->bind_param("ii", $student_id, $seat_number);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Seat booked successfully!", "seat" => $seat_number, "floor" => $floor]);
        } else {
            echo json_encode(["status" => "error", "message" => "Booking failed. Please try again."]);
        }
    }
    $stmt->close();
    exit;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Reservation</title>
    <!-- <link rel="stylesheet" type="text/css" href="mystyle.css"> -->
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
<label for="floorSelect">Select Floor:</label>
<select id="floorSelect">
    <option value="floor1">Floor 1</option>
    <option value="floor2">Floor 2</option>
    <option value="floor3">Floor 3</option>
</select>
</div>

<div class="main-content">
<div class="header">
    <h1>Seat Reservation</h1>
</div>
<div class="main">
    <div class="seat-container">
        <div class="section boys">
            <h2>Boys Section</h2>
            <div class="seat-grid" id="boysGrid"></div>
            <div class="drinking-water">Drinking Water</div>
        </div>

        <div class="section girls">
            <h2>Girls Section</h2>
            <div class="seat-grid" id="girlsGrid"></div>
            <div class="drinking-water">Drinking Water</div>
        </div>

        <div class="section both">
            <h2>Boys & Girls Section</h2>
            <div class="seat-grid" id="bothGrid"></div>
            <div class="drinking-water">Drinking Water</div>
        </div>
    </div>
    <label for="studentId">Student ID:</label>
    <input type="text" id="studentId" placeholder="Enter your student ID">


    <button class="confirm-btn" id="confirmBtn" disabled>Confirm Reservation</button>
    <button class="confirm-btn" onclick="window.location.href='../home _page/home.html'">Go Back</button>
</div>
<div class="footer">
    &copy; 2025 Seat Reservation System
</div>
</div>

<!-- Instructions Box -->
<div class="instructions">
<p><div class="checkbox red"></div><label>Booked</label></p>
<p><div class="checkbox green"></div><label>Selected</label></p>
<p><div class="checkbox gray"></div><label>Available</label></p>
</div>
    <script>
    const confirmBtn = document.getElementById('confirmBtn');
    const bookedSeats = new Set();
    const selectedSeats = new Set();

    // Function to generate the seats
    function generateSeats(gridId, start, count) {
        const seatGrid = document.getElementById(gridId);
        seatGrid.innerHTML = ''; // Clear the grid before generating new seats

        let seatCount = 0;

        for (let i = start; i < start + count; i++) {
            const seat = document.createElement('div');
            seat.classList.add('seat', 'available');
            seat.textContent = i;
            seat.dataset.index = i;

            seat.addEventListener('click', () => toggleSeatSelection(seat));

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
    }

    // Function to toggle seat selection
    function toggleSeatSelection(seat) {
        const seatIndex = seat.dataset.index;

        if (bookedSeats.has(seatIndex)) {
            alert('This seat is already booked.');
            return;
        }

        if (selectedSeats.has(seatIndex)) {
            selectedSeats.delete(seatIndex);
            seat.classList.remove('selected');
        } else {
            selectedSeats.add(seatIndex);
            seat.classList.add('selected');
        }

        confirmBtn.disabled = selectedSeats.size === 0;
    }

    // Event listener for floor selection
    const floorSelect = document.getElementById('floorSelect');
    floorSelect.addEventListener('change', function() {
        const selectedFloor = this.value;
        if (selectedFloor === 'floor1') {
            generateSeats('boysGrid', 1, 30);
            generateSeats('girlsGrid', 31, 30);
            generateSeats('bothGrid', 61, 40);
        } else if (selectedFloor === 'floor2') {
            generateSeats('boysGrid', 101, 30);
            generateSeats('girlsGrid', 131, 30);
            generateSeats('bothGrid', 161, 40);
        } else if (selectedFloor === 'floor3') {
            generateSeats('boysGrid', 201, 30);
            generateSeats('girlsGrid', 231, 30);
            generateSeats('bothGrid', 261, 40);
        }
    });

    // Initially generate seats for floor 1 (default)
    generateSeats('boysGrid', 1, 30);
    generateSeats('girlsGrid', 31, 30);
    generateSeats('bothGrid', 61, 40);

    // Confirm seat reservation button
    document.getElementById('confirmBtn').addEventListener('click', () => {
        const studentId = document.getElementById('studentId').value; // Get student ID
        if (!studentId) {
            alert("Please enter your Student ID.");
            return;
        }

        selectedSeats.forEach(seatIndex => {
            document.querySelectorAll('.seat').forEach(seat => {
                if (seat.dataset.index == seatIndex) {
                    seat.classList.remove('available', 'selected');
                    seat.classList.add('booked');
                }
            });
        });

        selectedSeats.clear();
        confirmBtn.disabled = true;
        const selectedSeat = [selectedSeats][0];
        const selectedFloor = document.getElementById('floorSelect').value;

        fetch('http://localhost/library_seat_reservation', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `seat_number=${selectedSeat}&student_id=${studentId}&floor=${selectedFloor}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                window.location.href = `seat_confirmation.php?message=${encodeURIComponent(data.message)}&seat=${data.seat}&floor=${data.floor}`;
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>

</body>
</html>
