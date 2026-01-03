<?php


require_once '../includes/auth_check.php';
require_once '../config/db.php';

$page_title = 'Employee Dashboard';


$stmt = $conn->prepare("SELECT COUNT(*) as total_leaves FROM leaves WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$total_leaves = $result->fetch_assoc()['total_leaves'];
$stmt->close();


$stmt = $conn->prepare("SELECT COUNT(*) as pending_leaves FROM leaves WHERE user_id = ? AND status = 'pending'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$pending_leaves = $result->fetch_assoc()['pending_leaves'];
$stmt->close();


$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT * FROM attendance WHERE user_id = ? AND date = ?");
$stmt->bind_param("is", $user_id, $today);
$stmt->execute();
$result = $stmt->get_result();
$today_attendance = $result->fetch_assoc();
$stmt->close();


$stmt = $conn->prepare("SELECT * FROM leaves WHERE user_id = ? ORDER BY applied_at DESC LIMIT 5");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent_leaves = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

include '../includes/header.php';
?>

<h2>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>

<div class="dashboard-stats">
    <div class="stat-card">
        <h3>Today's Attendance</h3>
        <?php if ($today_attendance): ?>
            <p class="stat-value"><?php echo ucfirst($today_attendance['status']); ?></p>
            <?php if ($today_attendance['check_in']): ?>
                <p>Check-in: <?php echo date('H:i', strtotime($today_attendance['check_in'])); ?></p>
            <?php endif; ?>
            <?php if ($today_attendance['check_out']): ?>
                <p>Check-out: <?php echo date('H:i', strtotime($today_attendance['check_out'])); ?></p>
            <?php endif; ?>
        <?php else: ?>
            <p class="stat-value">Not Checked In</p>
            <a href="/dayflow/employee/attendance.php" class="btn btn-primary">Check In Now</a>
        <?php endif; ?>
    </div>
    
    <div class="stat-card">
        <h3>Leave Requests</h3>
        <p class="stat-value"><?php echo $total_leaves; ?></p>
        <p>Total Leaves</p>
        <p>Pending: <?php echo $pending_leaves; ?></p>
    </div>
</div>

<div class="dashboard-section">
    <h3>Recent Leave Requests</h3>
    <?php if (count($recent_leaves) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Days</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_leaves as $leave): ?>
                    <tr>
                        <td><?php echo ucfirst($leave['leave_type']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($leave['start_date'])); ?></td>
                        <td><?php echo date('M d, Y', strtotime($leave['end_date'])); ?></td>
                        <td><?php echo $leave['days']; ?></td>
                        <td><span class="status status-<?php echo $leave['status']; ?>"><?php echo ucfirst($leave['status']); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No leave requests yet. <a href="/dayflow/employee/leave.php">Apply for leave</a></p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

