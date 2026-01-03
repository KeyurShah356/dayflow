<?php

session_start();

if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['role'];
    if ($role == 'admin') {
        header('Location: /dayflow/admin/dashboard.php');
    } else {
        header('Location: /dayflow/employee/dashboard.php');
    }
    exit();
}

header('Location: /dayflow/auth/login.php');
exit();
?>

