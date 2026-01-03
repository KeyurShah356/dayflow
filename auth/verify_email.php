<?php


session_start();

require_once '../config/db.php';

$error = '';
$success = '';
$token = $_GET['token'] ?? '';

if (empty($token)) {
    $error = 'Invalid verification link';
} else {
   
    $stmt = $conn->prepare("SELECT id, email, email_verified FROM users WHERE verification_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if ($user['email_verified']) {
            $success = 'Your email is already verified. You can now <a href="/dayflow/auth/login.php">login</a>.';
        } else {
            
            $stmt2 = $conn->prepare("UPDATE users SET email_verified = 1, verification_token = NULL WHERE id = ?");
            $stmt2->bind_param("i", $user['id']);
            
            if ($stmt2->execute()) {
                $success = 'Email verified successfully! You can now <a href="/dayflow/auth/login.php">login</a>.';
            } else {
                $error = 'Verification failed. Please try again.';
            }
            $stmt2->close();
        }
    } else {
        $error = 'Invalid or expired verification token';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - StaffSync</title>
    <link rel="stylesheet" href="/dayflow/assets/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-box">
            <h1>StaffSync</h1>
            <h2>Email Verification</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <p class="auth-link" style="text-align: center; margin-top: 1rem;">
                    <a href="/dayflow/auth/login.php">Go to Login</a>
                </p>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

