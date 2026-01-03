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
$login_type = $_GET['type'] ?? 'employee'; 
$login_identifier = $_POST['identifier'] ?? '';
$password = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';
    $selected_role = $_POST['role'] ?? 'employee';
    

    if (empty($login_identifier) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
       
        $is_email = filter_var($login_identifier, FILTER_VALIDATE_EMAIL);
        
       
        if ($is_email) {
            $stmt = $conn->prepare("SELECT id, employee_id, name, email, password, role FROM users WHERE email = ? AND role = ?");
        } else {
            $stmt = $conn->prepare("SELECT id, employee_id, name, email, password, role FROM users WHERE employee_id = ? AND role = ?");
        }
        
        $stmt->bind_param("ss", $login_identifier, $selected_role);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
          
            if (password_verify($password, $user['password'])) {
               
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['employee_id'] = $user['employee_id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
               
                if ($user['role'] == 'admin') {
                    header('Location: /dayflow/admin/dashboard.php');
                } else {
                    header('Location: /dayflow/employee/dashboard.php');
                }
                exit();
            } else {
                $error = 'Invalid credentials. Please check your Employee ID/Email and password.';
            }
        } else {
            $error = 'Invalid credentials. Please check your Employee ID/Email and password.';
        }
        $stmt->close();
    }
    
   
    $login_type = $selected_role;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - StaffSync</title>
    <link rel="stylesheet" href="/dayflow/assets/style.css">
    <style>
        .login-tabs {
            display: flex;
            gap: 0;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #ddd;
        }
        
        .login-tab {
            flex: 1;
            padding: 1rem;
            text-align: center;
            background-color: #f8f9fa;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            color: #666;
            transition: all 0.3s;
        }
        
        .login-tab:hover {
            background-color: #e9ecef;
        }
        
        .login-tab.active {
            background-color: white;
            color: #3498db;
            border-bottom-color: #3498db;
        }
        
        .login-form-container {
            display: none;
        }
        
        .login-form-container.active {
            display: block;
        }
        
        .password-requirements {
            font-size: 0.875rem;
            color: #666;
            margin-top: 0.5rem;
            padding: 0.75rem;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        
        .password-requirements ul {
            margin: 0.5rem 0 0 1.5rem;
            padding: 0;
        }
        
        .password-requirements li {
            margin: 0.25rem 0;
        }
        
        .identifier-hint {
            font-size: 0.875rem;
            color: #666;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-box">
            <h1>StaffSync</h1>
            <h2>Login</h2>
            
           
            <div class="login-tabs">
                <button type="button" class="login-tab <?php echo $login_type == 'employee' ? 'active' : ''; ?>" onclick="switchLoginType('employee')">
                    Employee Login
                </button>
                <button type="button" class="login-tab <?php echo $login_type == 'admin' ? 'active' : ''; ?>" onclick="switchLoginType('admin')">
                    Admin Login
                </button>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
         
            <form method="POST" action="" id="employee-form" class="login-form-container <?php echo $login_type == 'employee' ? 'active' : ''; ?>">
                <input type="hidden" name="role" value="employee">
                
                <div class="form-group">
                    <label for="employee-identifier">Employee ID or Email *</label>
                    <input type="text" id="employee-identifier" name="identifier" required 
                           value="<?php echo htmlspecialchars($login_identifier); ?>"
                           placeholder="Enter Employee ID or Email">
                    <div class="identifier-hint">You can login with your Employee ID or registered Email</div>
                </div>
                
                <div class="form-group">
                    <label for="employee-password">Password *</label>
                    <input type="password" id="employee-password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Login as Employee</button>
            </form>
            
   
            <form method="POST" action="" id="admin-form" class="login-form-container <?php echo $login_type == 'admin' ? 'active' : ''; ?>">
                <input type="hidden" name="role" value="admin">
                
                <div class="form-group">
                    <label for="admin-identifier">Employee ID or Email *</label>
                    <input type="text" id="admin-identifier" name="identifier" required 
                           value="<?php echo htmlspecialchars($login_identifier); ?>"
                           placeholder="Enter Employee ID or Email">
                    <div class="identifier-hint">You can login with your Employee ID or registered Email</div>
                </div>
                
                <div class="form-group">
                    <label for="admin-password">Password *</label>
                    <input type="password" id="admin-password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; background-color: #e74c3c;">Login as Admin</button>
            </form>
            
            <p class="auth-link" style="text-align: center; margin-top: 1.5rem;">
                Don't have an account? <a href="/dayflow/auth/register.php">Register here</a>
            </p>
            
            <div class="demo-info">
                <p><strong>Demo Admin:</strong></p>
                <p>Email/ID: admin@dayflow.com or ADMIN001</p>
                <p>Password: admin123</p>
            </div>
        </div>
    </div>
    
    <script>
        function switchLoginType(type) {
       
            document.querySelectorAll('.login-tab').forEach((tab, index) => {
                tab.classList.remove('active');
                if ((type === 'employee' && index === 0) || (type === 'admin' && index === 1)) {
                    tab.classList.add('active');
                }
            });
            
  
            document.getElementById('employee-form').classList.remove('active');
            document.getElementById('admin-form').classList.remove('active');
            
            if (type === 'employee') {
                document.getElementById('employee-form').classList.add('active');
            } else {
                document.getElementById('admin-form').classList.add('active');
            }
            
    
            const errorDiv = document.querySelector('.alert-error');
            if (errorDiv) {
                errorDiv.remove();
            }
            
      
            document.getElementById('employee-identifier').value = '';
            document.getElementById('employee-password').value = '';
            document.getElementById('admin-identifier').value = '';
            document.getElementById('admin-password').value = '';
        }
    </script>
</body>
</html>
