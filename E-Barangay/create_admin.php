<?php
require 'db.php';

$full_name = "Admin Ytang";
$gender = "male";
$age = 30;
$email = "admin@barangay.com";
$plain_password = "admin123";
$hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);
$role = "admin";

$stmt = $conn->prepare("INSERT INTO users (full_name, gender, age, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssisss", $full_name, $gender, $age, $email, $hashed_password, $role);

if ($stmt->execute()) {
    echo "Admin created!";
} else {
    echo "Error: " . $stmt->error;
}
?>
