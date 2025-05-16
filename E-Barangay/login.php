<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password_input = $_POST['password'] ?? '';

    if (empty($email) || empty($password_input)) {
        $_SESSION['error_message'] = "Please fill in both fields.";
        header("Location: index.php");
        exit;
    }

    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt_time'] = time();
    }

    if ($_SESSION['login_attempts'] >= 3) {
        $time_since_last = time() - $_SESSION['last_attempt_time'];
        if ($time_since_last < 60) {
            $remaining = 60 - $time_since_last;
            $_SESSION['error_message'] = "Too many failed attempts. Try again in {$remaining} seconds.";
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['login_attempts'] = 0;
        }
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password_input, $user['password'])) {
        // Handle "Remember Me"
        if (!empty($_POST['remember_me'])) {
            setcookie('remembered_email', $email, time() + (86400 * 2), "/"); // 2 days
        } else {
            setcookie('remembered_email', '', time() - 3600, "/"); // Remove if not checked
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['profile_image'] = $user['profile_image'] ?? 'default.png'; // Use default if no profile image
        $_SESSION['logged_in'] = true;
        $_SESSION['login_attempts'] = 0;

        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            $_SESSION['user'] = $user;
            header("Location: userpage.php");
        }
        exit;
    } else {
        $_SESSION['login_attempts'] += 1;
        $_SESSION['last_attempt_time'] = time();
        $_SESSION['error_message'] = "Invalid email or password.";
        $_SESSION['last_email'] = $email;
        header("Location: index.php");
        exit;
    }
}
?>