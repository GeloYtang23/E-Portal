<?php
session_start();
include 'db.php';
include 'encryption.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id']; // make sure user_id is stored in session
    $feedback = $_POST['feedback'];
    $key = "AES-25Q-CBX";

    // Encrypt the feedback message
    $encrypted = encryptMessage($feedback, $key);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO feedback (user_id, encrypted_message) VALUES (?, ?)");
    $stmt->bind_param("is", $userId, $encrypted);

    if ($stmt->execute()) {
        // Redirect to userpage.php with success message
        header("Location: userpage.php?feedback=success");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
