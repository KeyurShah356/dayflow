<?php


require_once '../includes/auth_check.php';
require_once '../config/db.php';


if ($user_role != 'admin') {
    header('Location: /dayflow/employee/dashboard.php');
    exit();
}

$page_title = 'Employee Management';

$success = '';
$error = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_employee'])) {
    $edit_user_id = intval($_POST['user_id']);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $hire_date = !empty($_POST['hire_date']) ? $_POST['hire_date'] : null;
    
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $email, $edit_user_id);
    $stmt->execute();
    $stmt->close();
    
   
    $stmt = $conn->prepare("INSERT INTO employee_profiles (user_id, phone, address, position, department, hire_date) 
                            VALUES (?, ?, ?, ?, ?, ?) 
                            ON DUPLICATE KEY UPDATE phone = ?, address = ?, position = ?, department = ?, hire_date = ?");
    $stmt->bind_param("issssssssss", $edit_user_id, $phone, $address, $position, $department, $hire_date, 
                      $phone, $address, $position, $department, $hire_date);
    
    if ($stmt->execute()) {
        $success = 'Employee updated successfully';
    } else {
        $error = 'Failed to update employee';
    }
    $stmt->close();
}


$stmt = $conn->query("SELECT u.id, u.employee_id, u.name, u.email, u.role, u.created_at,
                      ep.phone, ep.address, ep.position, ep.department, ep.hire_date, ep.profile_picture
                      FROM users u
                      LEFT JOIN employee_profiles ep ON u.id = ep.user_id
                      WHERE u.role = 'employee'
                      ORDER BY u.name");
$employees = $stmt->fetch_all(MYSQLI_ASSOC);
$stmt->close();


$edit_employee = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT u.*, ep.phone, ep.address, ep.position, ep.department, ep.hire_date, ep.profile_picture
                            FROM users u
                            LEFT JOIN employee_profiles ep ON u.id = ep.user_id
                            WHERE u.id = ? AND u.role = 'employee'");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_employee = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

include '../includes/header.php';
?>

<h2>Employee Management</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if ($edit_employee): ?>
    <div class="dashboard-section">
        <h3>Edit Employee: <?php echo htmlspecialchars($edit_employee['name']); ?></h3>
        <form method="POST" action="">
            <input type="hidden" name="user_id" value="<?php echo $edit_employee['id']; ?>">
            
            <div class="form-group">
                <label for="employee_id">Employee ID</label>
                <input type="text" id="employee_id" value="<?php echo htmlspecialchars($edit_employee['employee_id']); ?>" disabled>
                <small>Employee ID cannot be changed</small>
            </div>
            
            <div class="form-group">
                <label for="name">Name *</label>
                <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($edit_employee['name']); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($edit_employee['email']); ?>">
            </div>
            
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($edit_employee['phone'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($edit_employee['address'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="position">Position</label>
                <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($edit_employee['position'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" id="department" name="department" value="<?php echo htmlspecialchars($edit_employee['department'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="hire_date">Hire Date</label>
                <input type="date" id="hire_date" name="hire_date" value="<?php echo $edit_employee['hire_date'] ?? ''; ?>">
                <small>Set the employee's hire date. This will be visible on their profile.</small>
            </div>
            
            <button type="submit" name="update_employee" class="btn btn-primary">Update Employee</button>
            <a href="/dayflow/admin/employees.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
<?php endif; ?>

<div class="dashboard-section">
    <h3>All Employees (<?php echo count($employees); ?>)</h3>
    <?php if (count($employees) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Hire Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $emp): ?>
                    <tr>
                        <td>
                            <?php if (!empty($emp['profile_picture'])): ?>
                                <img src="../<?php echo htmlspecialchars($emp['profile_picture']); ?>" 
                                     alt="Profile" 
                                     class="employee-photo-thumb">
                            <?php else: ?>
                                <div class="employee-photo-placeholder-thumb">No Photo</div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($emp['employee_id']); ?></td>
                        <td><?php echo htmlspecialchars($emp['name']); ?></td>
                        <td><?php echo htmlspecialchars($emp['email']); ?></td>
                        <td><?php echo ucfirst($emp['role']); ?></td>
                        <td><?php echo htmlspecialchars($emp['phone'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($emp['address'] ?? '-'); ?></td>
                        <td><?php echo $emp['hire_date'] ? date('M d, Y', strtotime($emp['hire_date'])) : 'Not set'; ?></td>
                        <td>
                            <a href="/dayflow/admin/employees.php?edit=<?php echo $emp['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="/dayflow/admin/payroll.php?user_id=<?php echo $emp['id']; ?>" class="btn btn-sm btn-secondary">Payroll</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No employees found.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

