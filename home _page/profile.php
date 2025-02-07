<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['student_id'])) {
    echo "<script>alert('Session expired. Please log in again.'); window.location.href='../front_login_pages/loginpage.php';</script>";
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch user details
$sql = "SELECT * FROM student_details WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];

        $update_sql = "UPDATE student_details SET name=?, email=?, gender=? WHERE student_id=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssss", $name, $email, $gender, $student_id);
        if ($stmt->execute()) {
            echo "Profile updated successfully!";
        } else {
            echo "Error updating profile!";
        }
        $stmt->close();
        exit();
    }

    // Handle profile picture upload
    if (isset($_FILES['profile_pic'])) {
        $target_dir = "../assets/uploads/";
        $file_name = basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . $file_name;

        // Check if the directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            $update_pic_sql = "UPDATE student_details SET profile_pic=? WHERE student_id=?";
            $stmt = $conn->prepare($update_pic_sql);
            $stmt->bind_param("ss", $file_name, $student_id);
            $stmt->execute();
            $stmt->close();
            echo "Profile picture updated!";
        } else {
            echo "Error uploading profile picture!";
        }
        exit();
    }

    // Handle profile deletion
if (isset($_POST['delete_profile'])) {
    // Disable foreign key checks before deleting
    $disable_fk_sql = "SET FOREIGN_KEY_CHECKS = 0";
    $conn->query($disable_fk_sql);

    $delete_sql = "DELETE FROM student_details WHERE student_id=?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("s", $student_id);
    if ($stmt->execute()) {
        session_destroy();
        echo "<script>alert('Profile deleted. Redirecting...'); window.location.href='../front_login_pages/loginpage.php';</script>";
    } else {
        echo "Error deleting profile!";
    }
    $stmt->close();

    // Enable foreign key checks after deleting
    $enable_fk_sql = "SET FOREIGN_KEY_CHECKS = 1";
    $conn->query($enable_fk_sql);

    exit();
}

    // Handle logout
    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: ../front_login_pages/loginpage.php");
        exit();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <style>
        body {
    font-family: 'Poppins', sans-serif;
    background-color: #001F3F; /* Dark navy blue for a professional look */
    color: white;
    text-align: center;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.profile-container {
    background: rgba(255, 255, 255, 0.1); /* Semi-transparent background */
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
    max-width: 400px;
    width: 90%;
    text-align: center;
    backdrop-filter: blur(10px);
}

h2 {
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.profile-img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid white;
    cursor: pointer;
    transition: transform 0.3s ease-in-out;
}

.profile-img:hover {
    transform: scale(1.05);
}

input[type="text"] {
    width: 100%;
    background: transparent;
    border: none;
    border-bottom: 2px solid white;
    color: white;
    font-size: 16px;
    padding: 5px;
    text-align: center;
    outline: none;
    transition: border-bottom 0.3s ease-in-out;
}

input[type="text"]:focus {
    border-bottom: 2px solid #00BFFF; /* Light blue */
}

button {
    display: block;
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s ease-in-out, transform 0.2s;
}

.edit-btn {
    background-color: #FFC107; /* Yellow */
    color: #333;
}

.save-btn {
    background-color: #28A745; /* Green */
    color: white;
}

.delete-btn {
    background-color: #DC3545; /* Red */
    color: white;
}

button:hover {
    transform: scale(1.05);
    opacity: 0.9;
}

.logout-btn {
    background-color: #007BFF; /* Blue */
    color: white;
}

@media (max-width: 600px) {
    .profile-container {
        width: 95%;
        padding: 20px;
    }

    h2 {
        font-size: 22px;
    }
}

    </style>
</head>
<body>

<div class="profile-container">
    <h2>Profile</h2>
    <?php $profilePic = "../assets/uploads/default.jpg";
        if (!empty($user['profile_pic'])) {
            $profilePic = "../assets/uploads/" . htmlspecialchars($user['profile_pic']);
        }
    ?>
    <img src="<?php echo $profilePic; ?>" class="profile-img" id="profilePic" onclick="document.getElementById('fileInput').click();">
    <input type="file" id="fileInput" style="display: none;" accept="image/*">

    <p>Name: <input id="userName" type="text" value="<?php echo htmlspecialchars($user['name']); ?>" disabled></p>
    <p>Email: <input id="email" type="text" value="<?php echo htmlspecialchars($user['email']); ?>" disabled></p>
    <p>Gender: <input id="gender" type="text" value="<?php echo htmlspecialchars($user['gender']); ?>" disabled></p>
    <p>Student ID: <input type="text" value="<?php echo htmlspecialchars($user['student_id']); ?>" disabled></p>

    <button onclick="editProfile()" class="edit-btn">Edit Profile</button>
    <button onclick="saveProfile()" class="save-btn">Save</button>
    <button onclick="deleteProfile()" class="delete-btn">Delete Profile</button>
    <button onclick="logout()">Log Out</button>
</div>

<script>
    function editProfile() {
        document.querySelectorAll('.profile-container input').forEach(input => input.removeAttribute('disabled'));
        document.querySelector('.save-btn').style.display = 'inline-block';
        document.querySelector('.delete-btn').style.display = 'inline-block';
        document.querySelector('.edit-btn').style.display = 'none';
    }

    function saveProfile() {
        const formData = new FormData();
        formData.append("update_profile", true);
        formData.append("name", document.getElementById("userName").value);
        formData.append("email", document.getElementById("email").value);
        formData.append("gender", document.getElementById("gender").value);

        fetch("profile.php", { method: "POST", body: formData })
            .then(res => res.text())
            .then(alert)
            .then(() => window.location.reload());
    }

    document.getElementById("fileInput").addEventListener("change", function () {
        const formData = new FormData();
        formData.append("profile_pic", this.files[0]);

        fetch("profile.php", { method: "POST", body: formData })
            .then(res => res.text())
            .then(alert)
            .then(() => window.location.reload());
    });

    function deleteProfile() {
    if (!confirm("Are you sure you want to delete your profile?")) return;

    fetch("profile.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({ delete_profile: true })
    })
    .then(res => res.text())
    .then(alert)
    .then(() => window.location.href = "../front_login_pages/loginpage.php");
}

    function logout() {
    fetch("profile.php", { method: "POST", body: new URLSearchParams({ logout: true }) })
        .then(() => window.location.href = "../front_login_pages/loginpage.php")
        .then(() => alert("Logged out successfully!"));
}
</script>

</body>
</html>
