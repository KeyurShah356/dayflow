<?php


require_once '../includes/auth_check.php';
require_once '../config/db.php';


if ($user_role != 'admin') {
    header('Location: /dayflow/employee/dashboard.php');
    exit();
}


date_default_timezone_set('Asia/Kolkata');

$page_title = 'Attendance Management';


$filter_date = $_GET['date'] ?? date('Y-m-d');
$filter_employee = $_GET['employee'] ?? '';
$view_employee_id = $_GET['view_employee'] ?? null;
$view_type = $_GET['view_type'] ?? 'weekly'; 


$query = "SELECT a.*, u.name, u.employee_id 
          FROM attendance a
          JOIN users u ON a.user_id = u.id
          WHERE 1=1";

$params = [];
$types = "";

if (!empty($filter_date)) {
    $query .= " AND a.date = ?";
    $params[] = $filter_date;
    $types .= "s";
}

if (!empty($filter_employee)) {
    $query .= " AND a.user_id = ?";
    $params[] = intval($filter_employee);
    $types .= "i";
}

$query .= " ORDER BY a.date DESC, u.name LIMIT 100";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$attendance_records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();


$stmt = $conn->query("SELECT id, name, employee_id FROM users WHERE role = 'employee' ORDER BY name");
$all_employees = $stmt->fetch_all(MYSQLI_ASSOC);
$stmt->close();


$view_employee = null;
$weekly_attendance = [];
$monthly_attendance = [];

if ($view_employee_id) {
    $stmt = $conn->prepare("SELECT id, name, employee_id FROM users WHERE id = ? AND role = 'employee'");
    $stmt->bind_param("i", $view_employee_id);
    $stmt->execute();
    $view_employee = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($view_employee) {
      
        $week_start = date('Y-m-d', strtotime('-7 days'));
        $stmt = $conn->prepare("SELECT * FROM attendance WHERE user_id = ? AND date >= ? ORDER BY date DESC");
        $stmt->bind_param("is", $view_employee_id, $week_start);
        $stmt->execute();
        $weekly_attendance = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
       
        $month_start = date('Y-m-01');
        $month_end = date('Y-m-t');
        $stmt = $conn->prepare("SELECT * FROM attendance WHERE user_id = ? AND date >= ? AND date <= ? ORDER BY date DESC");
        $stmt->bind_param("iss", $view_employee_id, $month_start, $month_end);
        $stmt->execute();
        $monthly_attendance = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}

include '../includes/header.php';
?>

<h2>Attendance Management</h2>

<div class="dashboard-section">
    <h3>Filter Attendance</h3>
    <form method="GET" action="" class="filter-form">
        <div class="form-row">
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($filter_date); ?>">
            </div>
            
            <div class="form-group">
                <label for="employee">Employee</label>
                <select id="employee" name="employee">
                    <option value="">All Employees</option>
                    <?php foreach ($all_employees as $emp): ?>
                        <option value="<?php echo $emp['id']; ?>" <?php echo ($filter_employee == $emp['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($emp['name'] . ' (' . $emp['employee_id'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="/dayflow/admin/attendance.php" class="btn btn-secondary">Clear</a>
            </div>
        </div>
    </form>
</div>

<div class="dashboard-section">
    <h3>Attendance Records</h3>
    <?php if (count($attendance_records) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Employee</th>
                    <th>Employee ID</th>
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
                        <td>
                            <a href="/dayflow/admin/attendance.php?view_employee=<?php echo $record['user_id']; ?>&view_type=weekly" 
                               style="color: #3498db; text-decoration: none;">
                                <?php echo htmlspecialchars($record['name']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($record['employee_id']); ?></td>
                        <td><?php echo $record['check_in'] ? date('H:i', strtotime($record['check_in'])) : '-'; ?></td>
                        <td><?php echo $record['check_out'] ? date('H:i', strtotime($record['check_out'])) : '-'; ?></td>
                        <td><span class="status status-<?php echo $record['status']; ?>"><?php echo ucfirst($record['status']); ?></span></td>
                        <td><?php echo htmlspecialchars($record['remarks'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No attendance records found for the selected criteria.</p>
    <?php endif; ?>
</div>

<?php if ($view_employee): ?>
    <div class="dashboard-section">
        <h3>Attendance Details: <?php echo htmlspecialchars($view_employee['name']); ?> (<?php echo htmlspecialchars($view_employee['employee_id']); ?>)</h3>
        
        <div style="margin-bottom: 1rem;">
            <a href="/dayflow/admin/attendance.php?view_employee=<?php echo $view_employee_id; ?>&view_type=weekly" 
               class="btn btn-sm <?php echo $view_type == 'weekly' ? 'btn-primary' : 'btn-secondary'; ?>">Weekly</a>
            <a href="/dayflow/admin/attendance.php?view_employee=<?php echo $view_employee_id; ?>&view_type=monthly" 
               class="btn btn-sm <?php echo $view_type == 'monthly' ? 'btn-primary' : 'btn-secondary'; ?>">Monthly</a>
            <a href="/dayflow/admin/attendance.php" class="btn btn-sm btn-secondary">Back to All</a>
        </div>
        
        <?php if ($view_type == 'weekly'): ?>
            <h4>Weekly Attendance (Last 7 Days)</h4>
            <?php if (count($weekly_attendance) > 0): ?>
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
                        <?php foreach ($weekly_attendance as $record): ?>
                            <tr>
                                <td><?php echo date('M d, Y (D)', strtotime($record['date'])); ?></td>
                                <td><?php echo $record['check_in'] ? date('H:i', strtotime($record['check_in'])) : '-'; ?></td>
                                <td><?php echo $record['check_out'] ? date('H:i', strtotime($record['check_out'])) : '-'; ?></td>
                                <td><span class="status status-<?php echo $record['status']; ?>"><?php echo ucfirst($record['status']); ?></span></td>
                                <td><?php echo htmlspecialchars($record['remarks'] ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No attendance records found for the last 7 days.</p>
            <?php endif; ?>
        <?php else: ?>
            <h4>Monthly Attendance (<?php echo date('F Y'); ?>)</h4>
            <?php if (count($monthly_attendance) > 0): ?>
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
                        <?php foreach ($monthly_attendance as $record): ?>
                            <tr>
                                <td><?php echo date('M d, Y (D)', strtotime($record['date'])); ?></td>
                                <td><?php echo $record['check_in'] ? date('H:i', strtotime($record['check_in'])) : '-'; ?></td>
                                <td><?php echo $record['check_out'] ? date('H:i', strtotime($record['check_out'])) : '-'; ?></td>
                                <td><span class="status status-<?php echo $record['status']; ?>"><?php echo ucfirst($record['status']); ?></span></td>
                                <td><?php echo htmlspecialchars($record['remarks'] ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p style="margin-top: 1rem;">
                    <strong>Total Days Present:</strong> <?php echo count(array_filter($monthly_attendance, function($r) { return $r['status'] == 'present'; })); ?> / <?php echo count($monthly_attendance); ?>
                </p>
            <?php else: ?>
                <p>No attendance records found for this month.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>

