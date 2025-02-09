<?php
session_start();
$conn = new mysqli("localhost", "root", "", "library_seat_reservation");

if ($conn->connect_error) {
    die("<script>alert('Database connection failed!');</script>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $password = $_POST['password']; // Entered password

    $sql = "SELECT * FROM student_details WHERE student_id = '$student_id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // ✅ Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['student_id'] = $row['student_id'];
            $_SESSION['name'] = $row['name'];
            
            // Log login time
            $login_time = date("Y-m-d H:i:s");
            $conn->query("INSERT INTO login_records (student_id, login_time) VALUES ('$student_id', '$login_time')");

            // ✅ Redirect to the correct home page path
            echo "<script>alert('Login successful! Redirecting...'); window.location.href = 'http://localhost/ProjectChauvukondiFirst/home_page/home.php';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect password! Please try again.');</script>";
        }
    } else {
        echo "<script>alert('User ID not found!');</script>";
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background-color: #002223; height: 100vh; display: flex; justify-content: center; align-items: center; }
        .container { display: flex; background: #fff; border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); overflow: hidden; width: 80%; max-width: 900px; }
        .form-section { width: 40%; padding: 70px; background: #fff; }
        .form-section h1 { font-size: 24px; color: #333; margin-bottom: 20px; text-align: center; }
        .form-section input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; }
        .form-section button { width: 100%; padding: 12px; background: #92213d; color: #fff; border: none; border-radius: 5px; font-size: 18px; cursor: pointer; }
        .form-section button:hover { background: #B43757; }
        .video-container { width: 60%; position: relative; overflow: hidden; background: #002223; display: flex; align-items: center; justify-content: center; padding: 10px; }
        .video { width: 100%; height: auto; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-section" style="background-color: rgb(0, 34, 35);">
            <h1>Login</h1>
            <form method="POST" action="loginpage.php">
                <input type="text" name="student_id" placeholder="User ID" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">LOGIN</button>
            </form>
            <h4>If you don't have an account</h4>
            <button type="button" onclick="window.location.href='../about/register1.php'">REGISTER</button>
        </div>
        <div class="video-container">
            <video class="video" autoplay muted>
                <source src="bookreading.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
</body>
</html>
