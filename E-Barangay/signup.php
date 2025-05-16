<?php
session_start();
include 'db.php'; // Database connection

// Sanitize inputs
$full_name = $_POST['full_name'] ?? '';
$birthdate = $_POST['birthdate'] ?? '';
$age = $_POST['age'] ?? 0;
$gender = $_POST['gender'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Hash the password bycrypt
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ...get other fields...
    $full_name = $_POST['full_name'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Handle profile image upload
    $profileImagePath = 'uploads/default.png'; // fallback
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $targetDir = "uploads/";
        $profileImageName = uniqid() . '_' . basename($_FILES["profile_image"]["name"]);
        $targetFilePath = $targetDir . $profileImageName;
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFilePath)) {
            $profileImagePath = $targetFilePath;
        }
    }

    // Save to database
    $stmt = $conn->prepare("INSERT INTO users (full_name, birthdate, gender, age, email, password, profile_image, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'user', NOW())");
    $stmt->bind_param("sssssss", $full_name, $birthdate, $gender, $age, $email, $password, $profileImagePath);
    if ($stmt->execute()) {
        $_SESSION['signup_success'] = "Registration successful!";
        $_SESSION['show_signup_modal'] = true;
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['signup_error'] = "Registration failed.";
        $_SESSION['show_signup_modal'] = true;
        header("Location: index.php");
        exit;
    }
}
?>