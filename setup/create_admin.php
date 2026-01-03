<?php


require_once '../config/db.php';

$stmt = $conn->prepare("SELECT id FROM users WHERE email = 'admin@dayflow.com'");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt->close();
    echo "Admin user already exists. Updating password and verifying email...\n";
    $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
    $email_verified = 1;
    $stmt = $conn->prepare("UPDATE users SET password = ?, email_verified = 1 WHERE email = 'admin@dayflow.com'");
    $stmt->bind_param("s", $hashed_password);
    $stmt->execute();
    echo "Admin password updated and email verified successfully!\n";
    $stmt->close();
} else {
    $stmt->close();
    echo "Creating admin user...\n";
    $employee_id = 'ADMIN001';
    $name = 'System Admin';
    $email = 'admin@dayflow.com';
    $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
    $role = 'admin';
    $email_verified = 1; 
    
    $stmt = $conn->prepare("INSERT INTO users (employee_id, name, email, password, role, email_verified) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $employee_id, $name, $email, $hashed_password, $role, $email_verified);
    
    if ($stmt->execute()) {
        echo "Admin user created successfully!\n";
        echo "Email: admin@dayflow.com\n";
        echo "Password: admin123\n";
    } else {
        echo "Error creating admin user: " . $conn->error . "\n";
    }
    $stmt->close();
}

$conn->close();
?>

