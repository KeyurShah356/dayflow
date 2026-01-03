<?php


require_once '../includes/auth_check.php';
require_once '../config/db.php';

$page_title = 'Leave Management';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply_leave'])) {
    $leave_type = $_POST['leave_type'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $remarks = trim($_POST['remarks'] ?? '');
    
    if (empty($leave_type) || empty($start_date) || empty($end_date)) {
        $error = 'Please fill in all required fields';
    } else {
       
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $end->modify('+1 day'); 
        $days = $start->diff($end)->days;
        
        if ($days <= 0) {
            $error = 'End date must be after start date';
        } else {
            $stmt = $conn->prepare("INSERT INTO leaves (user_id, leave_type, start_date, end_date, days, remarks) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssis", $user_id, $leave_type, $start_date, $end_date, $days, $remarks);
            
            if ($stmt->execute()) {
                $success = 'Leave application submitted successfully';
                
                $leave_type = $start_date = $end_date = $remarks = '';
            } else {
                $error = 'Failed to submit leave application';
            }
            $stmt->close();
        }
    }
}


$stmt = $conn->prepare("SELECT * FROM leaves WHERE user_id = ? ORDER BY applied_at DESC");
$stmt->bind_param("i", $user_id);
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
    <h3>Apply for Leave</h3>
    <form method="POST" action="" class="leave-form">
        <div class="form-group">
            <label for="leave_type">Leave Type *</label>
            <select id="leave_type" name="leave_type" required>
                <option value="">Select leave type</option>
                <option value="paid" <?php echo (($leave_type ?? '') == 'paid') ? 'selected' : ''; ?>>Paid Leave</option>
                <option value="sick" <?php echo (($leave_type ?? '') == 'sick') ? 'selected' : ''; ?>>Sick Leave</option>
                <option value="unpaid" <?php echo (($leave_type ?? '') == 'unpaid') ? 'selected' : ''; ?>>Unpaid Leave</option>
            </select>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="start_date">Start Date *</label>
                <input type="date" id="start_date" name="start_date" required value="<?php echo htmlspecialchars($start_date ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="end_date">End Date *</label>
                <input type="date" id="end_date" name="end_date" required value="<?php echo htmlspecialchars($end_date ?? ''); ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="remarks">Remarks</label>
            <textarea id="remarks" name="remarks" rows="3"><?php echo htmlspecialchars($remarks ?? ''); ?></textarea>
        </div>
        
        <button type="submit" name="apply_leave" class="btn btn-primary">Apply for Leave</button>
    </form>
</div>

<div class="dashboard-section">
    <h3>My Leave Requests</h3>
    <?php if (count($leave_requests) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Days</th>
                    <th>Status</th>
                    <th>Admin Comments</th>
                    <th>Applied On</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leave_requests as $leave): ?>
                    <tr>
                        <td><?php echo ucfirst($leave['leave_type']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($leave['start_date'])); ?></td>
                        <td><?php echo date('M d, Y', strtotime($leave['end_date'])); ?></td>
                        <td><?php echo $leave['days']; ?></td>
                        <td><span class="status status-<?php echo $leave['status']; ?>"><?php echo ucfirst($leave['status']); ?></span></td>
                        <td><?php echo htmlspecialchars($leave['admin_comments'] ?? '-'); ?></td>
                        <td><?php echo date('M d, Y H:i', strtotime($leave['applied_at'])); ?></td>
                    </tr>
                    <?php if ($leave['remarks']): ?>
                        <tr class="remarks-row">
                            <td colspan="7"><strong>Remarks:</strong> <?php echo htmlspecialchars($leave['remarks']); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No leave requests found. Apply for your first leave above.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

