<?php
session_start();
require 'db.php';

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name']);
    $profileImage = $_FILES['profile_image'];

    // Handle image upload
    if ($profileImage && $profileImage['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $profileImage['tmp_name'];
        $fileName = basename($profileImage['name']);
        $newFileName = uniqid() . '_' . $fileName;
        $uploadPath = 'img/' . $newFileName;

        if (move_uploaded_file($fileTmpPath, $uploadPath)) {
            // Update in DB
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, profile_image = ? WHERE id = ?");
            $stmt->bind_param("ssi", $fullName, $newFileName, $userId);
            $stmt->execute();

            // Update session
            $_SESSION['full_name'] = $fullName;
            $_SESSION['profile_image'] = $newFileName;
        }
    }

    header("Location: admin_dashboard.php");
    exit;
}
?>
