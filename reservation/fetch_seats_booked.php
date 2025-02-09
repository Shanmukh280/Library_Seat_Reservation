<?php
include 'db_connect.php';
date_default_timezone_set('Asia/Kolkata');
$currentHour = date('H');
$slotColumn = 'slot_' . ($currentHour - 9 + 1);

$query = "SELECT seat_id FROM library_time_slots WHERE $slotColumn IS NOT NULL";
$result = $conn->query($query);

$bookedSeats = [];
while ($row = $result->fetch_assoc()) {
    $bookedSeats[] = (int)$row['seat_id'];
}

echo json_encode($bookedSeats);
?>
