<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #00212A;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }

        .register-container {
            background-color: #fff;
            width: 500px;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            text-align: center;
            transform: translateY(-20px);
            animation: fadeIn 1s ease-out;
        }

        h2 {
            font-size: 2rem;
            color: #008080;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .form-group {
            position: relative;
            margin-bottom: 15px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border-radius: 10px;
            border: 1px solid #ddd;
            outline: none;
            background-color: #f9f9f9;
        }

        .submit-btn {
            background-color: #008080;
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            border: none;
            font-size: 1.1rem;
            width: 100%;
            cursor: pointer;
        }

        .login-link {
            margin-top: 15px;
            font-size: 1rem;
        }

        .login-link a {
            color: #008080;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "library_seat_reservation");

    if ($conn->connect_error) {
        die("<script>alert('Database connection failed!');</script>");
    }

    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $gender = $conn->real_escape_string($_POST['gender']);
    $student_id = $conn->real_escape_string($_POST['id']);

    $sql = "INSERT INTO student_details (student_id, name, email, password, gender) VALUES ('$student_id', '$name', '$email', '$password', '$gender')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful! Redirecting to login page...'); window.location.href = '../front_login_pages/loginpage.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }

    $conn->close();
}
?>

<div class="register-container">
    <h2>Register Your Account</h2>
    <form method="POST">
        <div class="form-group">
            <input type="text" name="name" required placeholder="Full Name" />
        </div>
        <div class="form-group">
            <input type="email" name="email" required placeholder="Email Address" />
        </div>
        <div class="form-group">
            <input type="password" name="password" required placeholder="Password" />
        </div>
        <div class="form-group">
            <input type="text" name="id" required placeholder="ID Number" />
        </div>
        <div class="form-group">
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <button type="submit" class="submit-btn">Register</button>
    </form>
    <p class="login-link">Already have an account? <a href="../front_login_pages/loginpage.php">Log in here</a></p>
</div>
</body>
</html>
