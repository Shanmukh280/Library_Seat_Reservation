<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profilePic'])) {
    $targetDir = "uploads/";
    $fileName = basename($_FILES["profilePic"]["name"]);
    $targetFile = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file is an image
    if (getimagesize($_FILES["profilePic"]["tmp_name"]) === false) {
        die("File is not an image.");
    }

    // Allow only certain file types
    if (!in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        die("Only JPG, JPEG, PNG & GIF files are allowed.");
    }

    // Move the uploaded file
    if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $targetFile)) {
        // Update the database
        $conn = new mysqli("localhost", "root", "", "library_seat_reservation");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $user_id = $_SESSION['user_id'];
        $sql = "UPDATE student_details SET profile_pic = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $fileName, $user_id);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        echo "Profile picture updated successfully!";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>