<?php


require_once '../includes/auth_check.php';
require_once '../config/db.php';

if ($user_role != 'admin') {
    header('Location: /dayflow/employee/dashboard.php');
    exit();
}

$page_title = 'Leave Management';

$success = '';
$error = '';


if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $leave_id = intval($_GET['id']);
    
    if ($action == 'approve' || $action == 'reject') {
        $status = $action == 'approve' ? 'approved' : 'rejected';
        $admin_comments = $_POST['admin_comments'] ?? '';
        
        $stmt = $conn->prepare("UPDATE leaves SET status = ?, admin_comments = ?, reviewed_at = NOW(), reviewed_by = ? WHERE id = ?");
        $stmt->bind_param("ssii", $status, $admin_comments, $user_id, $leave_id);
        
        if ($stmt->execute()) {
            $success = 'Leave request ' . $status . ' successfully';
            
            
            if ($status == 'approved') {
                $stmt2 = $conn->prepare("SELECT user_id, start_date, end_date FROM leaves WHERE id = ?");
                $stmt2->bind_param("i", $leave_id);
                $stmt2->execute();
                $leave_data = $stmt2->get_result()->fetch_assoc();
                $stmt2->close();
                
               
                $start = new DateTime($leave_data['start_date']);
                $end = new DateTime($leave_data['end_date']);
                $end->modify('+1 day');
                
                $date = clone $start;
                while ($date < $end) {
                    $date_str = $date->format('Y-m-d');
                    $stmt3 = $conn->prepare("INSERT INTO attendance (user_id, date, status) VALUES (?, ?, 'leave') 
                                             ON DUPLICATE KEY UPDATE status = 'leave'");
                    $stmt3->bind_param("is", $leave_data['user_id'], $date_str);
                    $stmt3->execute();
                    $stmt3->close();
                    $date->modify('+1 day');
                }
            }
        } else {
            $error = 'Failed to update leave request';
        }
        $stmt->close();
    }
}


$filter_status = $_GET['status'] ?? 'all';


$query = "SELECT l.*, u.name, u.employee_id 
          FROM leaves l
          JOIN users u ON l.user_id = u.id
          WHERE 1=1";

if ($filter_status != 'all') {
    $query .= " AND l.status = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $filter_status);
} else {
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$leave_requests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

include '../includes/header.php';
?>

<h2>Leave Management</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="dashboard-section">
    <h3>Filter Leave Requests</h3>
    <div class="filter-tabs">
        <a href="/dayflow/admin/leaves.php?status=all" class="<?php echo $filter_status == 'all' ? 'active' : ''; ?>">All</a>
        <a href="/dayflow/admin/leaves.php?status=pending" class="<?php echo $filter_status == 'pending' ? 'active' : ''; ?>">Pending</a>
        <a href="/dayflow/admin/leaves.php?status=approved" class="<?php echo $filter_status == 'approved' ? 'active' : ''; ?>">Approved</a>
        <a href="/dayflow/admin/leaves.php?status=rejected" class="<?php echo $filter_status == 'rejected' ? 'active' : ''; ?>">Rejected</a>
    </div>
</div>

<div class="dashboard-section">
    <h3>Leave Requests (<?php echo count($leave_requests); ?>)</h3>
    <?php if (count($leave_requests) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Days</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Applied On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leave_requests as $leave): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($leave['name']); ?><br><small><?php echo htmlspecialchars($leave['employee_id']); ?></small></td>
                        <td><?php echo ucfirst($leave['leave_type']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($leave['start_date'])); ?></td>
                        <td><?php echo date('M d, Y', strtotime($leave['end_date'])); ?></td>
                        <td><?php echo $leave['days']; ?></td>
                        <td><span class="status status-<?php echo $leave['status']; ?>"><?php echo ucfirst($leave['status']); ?></span></td>
                        <td><?php echo htmlspecialchars($leave['remarks'] ?? '-'); ?></td>
                        <td><?php echo date('M d, Y', strtotime($leave['applied_at'])); ?></td>
                        <td>
                            <?php if ($leave['status'] == 'pending'): ?>
                                <form method="POST" style="display: inline-block;" action="?action=approve&id=<?php echo $leave['id']; ?>">
                                    <textarea name="admin_comments" placeholder="Comments (optional)" rows="2" style="width: 200px; margin-bottom: 5px;"></textarea><br>
                                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                </form>
                                <form method="POST" style="display: inline-block;" action="?action=reject&id=<?php echo $leave['id']; ?>">
                                    <textarea name="admin_comments" placeholder="Comments (optional)" rows="2" style="width: 200px; margin-bottom: 5px;"></textarea><br>
                                    <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                </form>
                            <?php else: ?>
                                <?php if ($leave['admin_comments']): ?>
                                    <small><strong>Admin:</strong> <?php echo htmlspecialchars($leave['admin_comments']); ?></small>
                                <?php else: ?>
                                    <small>-</small>
                                <?php endif; ?>
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

