<?php


require_once '../includes/auth_check.php';
require_once '../config/db.php';


if ($user_role != 'admin') {
    header('Location: /dayflow/employee/dashboard.php');
    exit();
}

$page_title = 'Admin Dashboard';


$stmt = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'employee'");
$total_employees = $stmt->fetch_assoc()['total'];
$stmt->close();

$stmt = $conn->query("SELECT COUNT(*) as total FROM leaves WHERE status = 'pending'");
$pending_leaves = $stmt->fetch_assoc()['total'];
$stmt->close();

$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT COUNT(DISTINCT user_id) as total FROM attendance WHERE date = ?");
$stmt->bind_param("s", $today);
$stmt->execute();
$today_present = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();


$stmt = $conn->query("SELECT l.*, u.name, u.employee_id FROM leaves l 
                      JOIN users u ON l.user_id = u.id 
                      ORDER BY l.applied_at DESC LIMIT 10");
$recent_leaves = $stmt->fetch_all(MYSQLI_ASSOC);
$stmt->close();

include '../includes/header.php';
?>

<h2>Admin Dashboard</h2>

<div class="dashboard-stats">
    <div class="stat-card">
        <h3>Total Employees</h3>
        <p class="stat-value"><?php echo $total_employees; ?></p>
    </div>
    
    <div class="stat-card">
        <h3>Pending Leave Requests</h3>
        <p class="stat-value"><?php echo $pending_leaves; ?></p>
        <a href="/dayflow/admin/leaves.php" class="btn btn-primary">View All</a>
    </div>
    
    <div class="stat-card">
        <h3>Present Today</h3>
        <p class="stat-value"><?php echo $today_present; ?></p>
        <a href="/dayflow/admin/attendance.php" class="btn btn-primary">View Attendance</a>
    </div>
</div>

<div class="dashboard-section">
    <h3>Recent Leave Requests</h3>
    <?php if (count($recent_leaves) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Days</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_leaves as $leave): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($leave['name']); ?> (<?php echo htmlspecialchars($leave['employee_id']); ?>)</td>
                        <td><?php echo ucfirst($leave['leave_type']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($leave['start_date'])); ?></td>
                        <td><?php echo date('M d, Y', strtotime($leave['end_date'])); ?></td>
                        <td><?php echo $leave['days']; ?></td>
                        <td><span class="status status-<?php echo $leave['status']; ?>"><?php echo ucfirst($leave['status']); ?></span></td>
                        <td>
                            <?php if ($leave['status'] == 'pending'): ?>
                                <a href="/dayflow/admin/leaves.php?action=approve&id=<?php echo $leave['id']; ?>" class="btn btn-sm btn-success">Approve</a>
                                <a href="/dayflow/admin/leaves.php?action=reject&id=<?php echo $leave['id']; ?>" class="btn btn-sm btn-danger">Reject</a>
                            <?php else: ?>
                                <a href="/dayflow/admin/leaves.php">View Details</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No leave requests found.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

