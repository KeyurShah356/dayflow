<?php


session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: /dayflow/auth/login.php');
    exit();
}

$user_role = $_SESSION['role'] ?? 'employee';
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'] ?? 'User';
$user_email = $_SESSION['email'] ?? '';
?>

