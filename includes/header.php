<?php

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StaffSync - <?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></title>
    <link rel="stylesheet" href="/dayflow/assets/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="header-content">
                <h1 class="logo">StaffSync</h1>
                <nav class="nav">
                    <?php if ($user_role == 'admin'): ?>
                        <a href="/dayflow/admin/dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
                        <a href="/dayflow/admin/employees.php" class="<?php echo $current_page == 'employees.php' ? 'active' : ''; ?>">Employees</a>
                        <a href="/dayflow/admin/attendance.php" class="<?php echo $current_page == 'attendance.php' ? 'active' : ''; ?>">Attendance</a>
                        <a href="/dayflow/admin/leaves.php" class="<?php echo $current_page == 'leaves.php' ? 'active' : ''; ?>">Leaves</a>
                        <a href="/dayflow/admin/payroll.php" class="<?php echo $current_page == 'payroll.php' ? 'active' : ''; ?>">Payroll</a>
                    <?php else: ?>
                        <a href="/dayflow/employee/dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
                        <a href="/dayflow/employee/profile.php" class="<?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">Profile</a>
                        <a href="/dayflow/employee/attendance.php" class="<?php echo $current_page == 'attendance.php' ? 'active' : ''; ?>">Attendance</a>
                        <a href="/dayflow/employee/leave.php" class="<?php echo $current_page == 'leave.php' ? 'active' : ''; ?>">Leave</a>
                        <a href="/dayflow/employee/payroll.php" class="<?php echo $current_page == 'payroll.php' ? 'active' : ''; ?>">Payroll</a>
                    <?php endif; ?>
                </nav>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($user_name); ?> (<?php echo $user_role; ?>)</span>
                    <a href="/dayflow/auth/logout.php" class="btn-logout">Logout</a>
                </div>
            </div>
        </header>
        <main class="main-content">

