<?php


session_start();
require_once '../config/db.php';

$error = '';
$success = '';
$email = $_GET['email'] ?? '';
$otp_verified = false;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $otp = trim($_POST['otp'] ?? '');
    
    if (empty($email) || empty($otp)) {
        $error = 'Please enter email and OTP';
    } else {
       
        $stmt = $conn->prepare("SELECT id, email, otp_code, otp_expires_at, email_verified FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            if ($user['email_verified']) {
                $success = 'Your email is already verified. You can now <a href="/dayflow/auth/login.php">login</a>.';
                $otp_verified = true;
            } elseif (empty($user['otp_code'])) {
                $error = 'No OTP found. Please request a new OTP.';
            } elseif (strtotime($user['otp_expires_at']) < time()) {
                $error = 'OTP has expired. Please request a new OTP.';
            } elseif ($user['otp_code'] === $otp) {
             
                $stmt2 = $conn->prepare("UPDATE users SET email_verified = 1, otp_code = NULL, otp_expires_at = NULL, verification_token = NULL WHERE id = ?");
                $stmt2->bind_param("i", $user['id']);
                
                if ($stmt2->execute()) {
                    $success = 'Email verified successfully! You can now <a href="/dayflow/auth/login.php">login</a>.';
                    $otp_verified = true;
                } else {
                    $error = 'Verification failed. Please try again.';
                }
                $stmt2->close();
            } else {
                $error = 'Invalid OTP code. Please try again.';
            }
        } else {
            $error = 'Email not found.';
        }
        $stmt->close();
    }
}


$email_value = $email ? htmlspecialchars($email) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - StaffSync</title>
    <link rel="stylesheet" href="/dayflow/assets/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-box">
            <h1>StaffSync</h1>
            <h2>Verify Email with OTP</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php elseif (!$otp_verified): ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required value="<?php echo $email_value; ?>" <?php echo $email ? 'readonly' : ''; ?>>
                    </div>
                    
                    <div class="form-group">
                        <label for="otp">OTP Code *</label>
                        <input type="text" id="otp" name="otp" required maxlength="6" pattern="[0-9]{6}" placeholder="Enter 6-digit OTP">
                        <small>Enter the 6-digit OTP sent to your email</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Verify OTP</button>
                </form>
                
                <p class="auth-link" style="margin-top: 1rem;">
                    <a href="/dayflow/auth/login.php">Back to Login</a> | 
                    <a href="/dayflow/auth/register.php">Register New Account</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

