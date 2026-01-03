<?php


require_once '../includes/auth_check.php';
require_once '../config/db.php';


if ($user_role != 'admin') {
    header('Location: /dayflow/employee/dashboard.php');
    exit();
}

$page_title = 'Payroll Management';

$success = '';
$error = '';
$edit_payroll = null;
$all_payrolls = [];


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_salary'])) {
    $edit_user_id = intval($_POST['user_id']);
    $basic_salary = floatval($_POST['basic_salary'] ?? 0);
    $allowances = floatval($_POST['allowances'] ?? 0);
    $deductions = floatval($_POST['deductions'] ?? 0);
    $net_salary = $basic_salary + $allowances - $deductions;
    
    $stmt = $conn->prepare("INSERT INTO payroll (user_id, basic_salary, allowances, deductions, net_salary, updated_by) 
                            VALUES (?, ?, ?, ?, ?, ?) 
                            ON DUPLICATE KEY UPDATE 
                            basic_salary = ?, allowances = ?, deductions = ?, net_salary = ?, updated_by = ?");
    $stmt->bind_param("iddddiddddi", $edit_user_id, $basic_salary, $allowances, $deductions, $net_salary, $user_id,
                      $basic_salary, $allowances, $deductions, $net_salary, $user_id);
    
    if ($stmt->execute()) {
        $success = 'Salary updated successfully';
       
        header('Location: /dayflow/admin/payroll.php?user_id=' . $edit_user_id . '&updated=1');
        exit();
    } else {
        $error = 'Failed to update salary: ' . $conn->error;
    }
    $stmt->close();
}


if (isset($_GET['updated']) && $_GET['updated'] == 1) {
    $success = 'Salary updated successfully! Changes are now visible on both admin and employee sides.';
}

$selected_user_id = $_GET['user_id'] ?? null;

if ($selected_user_id) {
  
    $stmt = $conn->prepare("SELECT u.id, u.name, u.employee_id, p.basic_salary, p.allowances, p.deductions, p.net_salary, p.updated_at
                            FROM users u
                            LEFT JOIN payroll p ON u.id = p.user_id
                            WHERE u.id = ? AND u.role = 'employee'");
    $stmt->bind_param("i", $selected_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_payroll = $result->fetch_assoc();
    $stmt->close();
    
   
    if ($edit_payroll && !isset($edit_payroll['basic_salary'])) {
        $edit_payroll['basic_salary'] = 0;
        $edit_payroll['allowances'] = 0;
        $edit_payroll['deductions'] = 0;
        $edit_payroll['net_salary'] = 0;
    }
} else {
  
    $stmt = $conn->query("SELECT u.id, u.name, u.employee_id, p.basic_salary, p.allowances, p.deductions, p.net_salary, p.updated_at
                          FROM users u
                          LEFT JOIN payroll p ON u.id = p.user_id
                          WHERE u.role = 'employee'
                          ORDER BY u.name");
    $all_payrolls = $stmt->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

include '../includes/header.php';
?>

<h2>Payroll Management</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if ($edit_payroll): ?>
    <div class="dashboard-section">
        <h3>Update Salary: <?php echo htmlspecialchars($edit_payroll['name']); ?></h3>
        <form method="POST" action="">
            <input type="hidden" name="user_id" value="<?php echo $edit_payroll['id']; ?>">
            
            <div class="form-group">
                <label for="employee_id">Employee ID</label>
                <input type="text" id="employee_id" value="<?php echo htmlspecialchars($edit_payroll['employee_id']); ?>" disabled>
            </div>
            
            <div class="form-group">
                <label for="basic_salary">Basic Salary (INR) *</label>
                <input type="number" id="basic_salary" name="basic_salary" step="0.01" min="0" required 
                       value="<?php echo number_format($edit_payroll['basic_salary'] ?? 0, 2, '.', ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="allowances">Allowances (INR)</label>
                <input type="number" id="allowances" name="allowances" step="0.01" min="0" 
                       value="<?php echo number_format($edit_payroll['allowances'] ?? 0, 2, '.', ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="deductions">Deductions (INR)</label>
                <input type="number" id="deductions" name="deductions" step="0.01" min="0" 
                       value="<?php echo number_format($edit_payroll['deductions'] ?? 0, 2, '.', ''); ?>">
            </div>
            
            <div class="form-group">
                <label>Net Salary (Auto-calculated)</label>
                <input type="text" id="net_salary_display" value="₹<?php echo number_format(($edit_payroll['basic_salary'] ?? 0) + ($edit_payroll['allowances'] ?? 0) - ($edit_payroll['deductions'] ?? 0), 2); ?>" disabled style="background: #f5f5f5; font-weight: bold; color: #27ae60;">
                <small>Net Salary = Basic Salary + Allowances - Deductions</small>
            </div>
            
            <button type="submit" name="update_salary" class="btn btn-primary">Update Salary</button>
            <a href="/dayflow/admin/payroll.php" class="btn btn-secondary">Back to All</a>
        </form>
        
        <script>
      
        document.getElementById('basic_salary').addEventListener('input', calculateNetSalary);
        document.getElementById('allowances').addEventListener('input', calculateNetSalary);
        document.getElementById('deductions').addEventListener('input', calculateNetSalary);
        
        function calculateNetSalary() {
            const basic = parseFloat(document.getElementById('basic_salary').value) || 0;
            const allowances = parseFloat(document.getElementById('allowances').value) || 0;
            const deductions = parseFloat(document.getElementById('deductions').value) || 0;
            const net = basic + allowances - deductions;
            document.getElementById('net_salary_display').value = '₹' + net.toFixed(2);
        }
        </script>
    </div>
<?php else: ?>
    <div class="dashboard-section">
        <h3>All Employee Salaries</h3>
        <?php if (count($all_payrolls) > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Employee ID</th>
                        <th>Basic Salary</th>
                        <th>Allowances</th>
                        <th>Deductions</th>
                        <th>Net Salary</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_payrolls as $payroll): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($payroll['name']); ?></td>
                            <td><?php echo htmlspecialchars($payroll['employee_id']); ?></td>
                            <td>₹<?php echo number_format($payroll['basic_salary'] ?? 0, 2); ?></td>
                            <td>₹<?php echo number_format($payroll['allowances'] ?? 0, 2); ?></td>
                            <td>₹<?php echo number_format($payroll['deductions'] ?? 0, 2); ?></td>
                            <td><strong>₹<?php echo number_format($payroll['net_salary'] ?? 0, 2); ?></strong></td>
                            <td><?php echo $payroll['updated_at'] ? date('M d, Y', strtotime($payroll['updated_at'])) : '-'; ?></td>
                            <td>
                                <a href="/dayflow/admin/payroll.php?user_id=<?php echo $payroll['id']; ?>" class="btn btn-sm btn-primary">Update</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No employees found.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>

