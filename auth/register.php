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

require_once '../config/db.php';

$error = '';
$success = '';
$password_errors = [];

function validatePassword($password) {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter';
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must contain at least one lowercase letter';
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one number';
    }
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = 'Password must contain at least one special character (!@#$%^&*)';
    }
    
    return $errors;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = trim($_POST['employee_id'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'employee';
    

    if (empty($employee_id) || empty($name) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {

        $password_errors = validatePassword($password);
        if (!empty($password_errors)) {
            $error = 'Password does not meet security requirements';
        } else {
         
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR employee_id = ?");
            $stmt->bind_param("ss", $email, $employee_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = 'Email or Employee ID already exists';
                $stmt->close();
            } else {
                $stmt->close();
                
               
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
               
                $stmt = $conn->prepare("INSERT INTO users (employee_id, name, email, password, role, email_verified) VALUES (?, ?, ?, ?, ?, 1)");
                $stmt->bind_param("sssss", $employee_id, $name, $email, $hashed_password, $role);
                
                if ($stmt->execute()) {
                    $user_id = $conn->insert_id;
                    
                    
                    $stmt2 = $conn->prepare("INSERT INTO employee_profiles (user_id) VALUES (?)");
                    $stmt2->bind_param("i", $user_id);
                    $stmt2->execute();
                    $stmt2->close();
                    
               
                    $stmt3 = $conn->prepare("INSERT INTO payroll (user_id) VALUES (?)");
                    $stmt3->bind_param("i", $user_id);
                    $stmt3->execute();
                    $stmt3->close();
                    
                    $success = 'Registration successful! You can now <a href="/dayflow/auth/login.php">login</a> to your account.';
                    $employee_id = $name = $email = '';
                    $error = 'Registration failed. Please try again.';
                }
                $stmt->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - StaffSync</title>
    <link rel="stylesheet" href="/dayflow/assets/style.css">
    <style>
        .password-requirements {
            font-size: 0.875rem;
            color: #666;
            margin-top: 0.5rem;
            padding: 0.75rem;
            background-color: #f8f9fa;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .password-requirements strong {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .password-requirements ul {
            margin: 0.5rem 0 0 1.5rem;
            padding: 0;
        }
        
        .password-requirements li {
            margin: 0.25rem 0;
        }
        
        .password-error {
            color: #e74c3c;
            font-weight: 500;
        }
    </style>
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-box">
            <h1>StaffSync</h1>
            <h2>Register</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                    <?php if (!empty($password_errors)): ?>
                        <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0;">
                            <?php foreach ($password_errors as $err): ?>
                                <li><?php echo htmlspecialchars($err); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success" style="word-wrap: break-word; overflow-wrap: break-word;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="registerForm">
                <div class="form-group">
                    <label for="employee_id">Employee ID *</label>
                    <input type="text" id="employee_id" name="employee_id" required value="<?php echo htmlspecialchars($employee_id ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($name ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required onkeyup="validatePasswordStrength()">
                    <div class="password-requirements">
                        <strong>Password must contain:</strong>
                        <ul>
                            <li id="req-length">At least 8 characters</li>
                            <li id="req-upper">One uppercase letter (A-Z)</li>
                            <li id="req-lower">One lowercase letter (a-z)</li>
                            <li id="req-number">One number (0-9)</li>
                            <li id="req-special">One special character (!@#$%^&*)</li>
                        </ul>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <div class="form-group">
                    <label for="role">Role *</label>
                    <select id="role" name="role" required>
                        <option value="employee" <?php echo (($role ?? 'employee') == 'employee') ? 'selected' : ''; ?>>Employee</option>
                        <option value="admin" <?php echo (($role ?? '') == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
            
            <p class="auth-link">
                Already have an account? <a href="/dayflow/auth/login.php">Login here</a>
            </p>
        </div>
    </div>
    
    <script>
        function validatePasswordStrength() {
            const password = document.getElementById('password').value;
            
            
            document.getElementById('req-length').style.color = password.length >= 8 ? '#27ae60' : '#666';
            document.getElementById('req-upper').style.color = /[A-Z]/.test(password) ? '#27ae60' : '#666';
            document.getElementById('req-lower').style.color = /[a-z]/.test(password) ? '#27ae60' : '#666';
            document.getElementById('req-number').style.color = /[0-9]/.test(password) ? '#27ae60' : '#666';
            document.getElementById('req-special').style.color = /[^A-Za-z0-9]/.test(password) ? '#27ae60' : '#666';
        }
    </script>
</body>
</html>
