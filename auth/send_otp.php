<?php

session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $response['message'] = 'Email is required';
        echo json_encode($response);
        exit();
    }
    
   
    $stmt = $conn->prepare("SELECT id, email_verified FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        $response['message'] = 'Email not found';
        $stmt->close();
        echo json_encode($response);
        exit();
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    

    $otp_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    

    $otp_expires = date('Y-m-d H:i:s', strtotime('+10 minutes'));
    

    $stmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expires_at = ? WHERE email = ?");
    $stmt->bind_param("sss", $otp_code, $otp_expires, $email);
    
    if ($stmt->execute()) {
     
        $response['success'] = true;
        $response['message'] = 'OTP sent successfully';
        $response['otp'] = $otp_code;
    } else {
        $response['message'] = 'Failed to send OTP';
    }
    $stmt->close();
} else {
    $response['message'] = 'Invalid request';
}

echo json_encode($response);
$conn->close();
?>

