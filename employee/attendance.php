<?php


require_once '../includes/auth_check.php';
require_once '../config/db.php';

$page_title = 'My Attendance';

$success = '';
$error = '';


date_default_timezone_set('Asia/Kolkata');


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['check_in'])) {
    $today = date('Y-m-d');
    $check_in_time = date('H:i:s');
    
   
    $stmt = $conn->prepare("SELECT id FROM attendance WHERE user_id = ? AND date = ?");
    $stmt->bind_param("is", $user_id, $today);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $error = 'You have already checked in today';
    } else {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO attendance (user_id, date, check_in, status) VALUES (?, ?, ?, 'present')");
        $stmt->bind_param("iss", $user_id, $today, $check_in_time);
        if ($stmt->execute()) {
            $success = 'Checked in successfully at ' . date('H:i');
        } else {
            $error = 'Failed to check in';
        }
    }
    $stmt->close();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['check_out'])) {
    $today = date('Y-m-d');
    $check_out_time = date('H:i:s');
    
    $stmt = $conn->prepare("UPDATE attendance SET check_out = ? WHERE user_id = ? AND date = ? AND check_out IS NULL");
    $stmt->bind_param("sis", $check_out_time, $user_id, $today);
    
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $success = 'Checked out successfully at ' . date('H:i');
    } else {
        $error = 'Failed to check out. You may not have checked in today.';
    }
    $stmt->close();
}


$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT * FROM attendance WHERE user_id = ? AND date = ?");
$stmt->bind_param("is", $user_id, $today);
$stmt->execute();
$today_attendance = $stmt->get_result()->fetch_assoc();
$stmt->close();


$stmt = $conn->prepare("SELECT * FROM attendance WHERE user_id = ? ORDER BY date DESC LIMIT 30");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$attendance_records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

include '../includes/header.php';
?>

<h2>My Attendance</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="attendance-actions">
    <div class="check-in-out">
        <h3>Today's Attendance</h3>
        <?php if ($today_attendance && $today_attendance['check_in']): ?>
            <p><strong>Status:</strong> <?php echo ucfirst($today_attendance['status']); ?></p>
            <p><strong>Check-in:</strong> <?php echo date('H:i', strtotime($today_attendance['check_in'])); ?></p>
            <?php if ($today_attendance['check_out']): ?>
                <p><strong>Check-out:</strong> <?php echo date('H:i', strtotime($today_attendance['check_out'])); ?></p>
            <?php else: ?>
                <form method="POST" style="display: inline;">
                    <button type="submit" name="check_out" class="btn btn-secondary">Check Out</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <p>Not checked in today</p>
            <form method="POST" style="display: inline;">
                <button type="submit" name="check_in" class="btn btn-primary">Check In</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="dashboard-section">
    <h3>Attendance History (Last 30 Days)</h3>
    <?php if (count($attendance_records) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendance_records as $record): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($record['date'])); ?></td>
                        <td><?php echo $record['check_in'] ? date('H:i', strtotime($record['check_in'])) : '-'; ?></td>
                        <td><?php echo $record['check_out'] ? date('H:i', strtotime($record['check_out'])) : '-'; ?></td>
                        <td><span class="status status-<?php echo $record['status']; ?>"><?php echo ucfirst($record['status']); ?></span></td>
                        <td><?php echo htmlspecialchars($record['remarks'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No attendance records found.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

