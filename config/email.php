<?php

function sendOTPEmail($to_email, $to_name, $otp_code) {
    $subject = "StaffSync - Email Verification OTP";
    
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #2c3e50; color: white; padding: 20px; text-align: center; }
            .content { background-color: #f9f9f9; padding: 30px; }
            .otp-box { background-color: #3498db; color: white; padding: 20px; text-align: center; font-size: 32px; font-weight: bold; margin: 20px 0; border-radius: 5px; }
            .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>StaffSync</h1>
            </div>
            <div class='content'>
                <h2>Email Verification</h2>
                <p>Hello " . htmlspecialchars($to_name) . ",</p>
                <p>Thank you for registering with StaffSync. Please use the following OTP to verify your email address:</p>
                <div class='otp-box'>" . htmlspecialchars($otp_code) . "</div>
                <p>This OTP will expire in 10 minutes.</p>
                <p>If you did not register for this account, please ignore this email.</p>
            </div>
            <div class='footer'>
                <p>&copy; " . date('Y') . " StaffSync. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: StaffSync <noreply@dayflow.com>" . "\r\n";
    $headers .= "Reply-To: noreply@dayflow.com" . "\r\n";
    
    // Send email
    return mail($to_email, $subject, $message, $headers);
}
?>

