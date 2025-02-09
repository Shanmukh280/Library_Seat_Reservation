<?php
// Database connection
$servername = "localhost"; // Change if necessary
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$database = "library_seat_reservation";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to count vacant seats
function countVacantSeats($conn, $startId, $endId) {
    date_default_timezone_set('Asia/Kolkata'); // Set timezone
    $currentHour = date('H');
    $slotColumn = 'slot_' . ($currentHour - 9 + 1);
    $query = "SELECT COUNT(*) AS vacant FROM library_time_slots 
              WHERE seat_id BETWEEN ? AND ? 
              AND $slotColumn IS NULL";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $startId, $endId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['vacant'];
    } else {
        $stmt->close();
        return 0; // Return 0 in case of an error
    }
}

// Get available seat counts
$boysVacant = countVacantSeats($conn, 1, 30);
$girlsVacant = countVacantSeats($conn, 31, 60);
$bothVacant = countVacantSeats($conn, 61, 100);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Availability</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #00202A;
            color: white;
            text-align: center;
            padding: 20px;
        }

        h1 {
            color: #ffeb3b;
        }

        .section {
            background-color: #003545;
            padding: 20px;
            margin: 15px auto;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .section h2 {
            margin-bottom: 10px;
        }

        .vacant-count {
            font-size: 2rem;
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
</head>
<body>

    <h1>Library Seat Availability</h1>

    <div class="section">
        <h2>Boys Section</h2>
        <p>Vacant Seats: <span class="vacant-count"><?php echo $boysVacant; ?></span></p>
    </div>

    <div class="section">
        <h2>Girls Section</h2>
        <p>Vacant Seats: <span class="vacant-count"><?php echo $girlsVacant; ?></span></p>
    </div>

    <div class="section">
        <h2>Both Section</h2>
        <p>Vacant Seats: <span class="vacant-count"><?php echo $bothVacant; ?></span></p>
    </div>

</body>
</html>
