<?php


session_start();


ob_start();

require_once '../includes/auth_check.php';
require_once '../config/db.php';


ob_clean();


header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}


$field = isset($_POST['field']) ? trim($_POST['field']) : '';
$value = isset($_POST['value']) ? trim($_POST['value']) : '';


$allowed_fields = ['phone', 'address'];
if (empty($field) || !in_array($field, $allowed_fields)) {
    echo json_encode(['success' => false, 'message' => 'Invalid field']);
    exit;
}


try {

    $stmt = $conn->prepare("SELECT id FROM employee_profiles WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    
    if ($result->num_rows === 0) {
        
        if ($field === 'phone') {
            $stmt = $conn->prepare("INSERT INTO employee_profiles (user_id, phone) VALUES (?, ?)");
        } else {
            $stmt = $conn->prepare("INSERT INTO employee_profiles (user_id, address) VALUES (?, ?)");
        }
        $stmt->bind_param("is", $user_id, $value);
    } else {
       
        if ($field === 'phone') {
            $stmt = $conn->prepare("UPDATE employee_profiles SET phone = ? WHERE user_id = ?");
        } else {
            $stmt = $conn->prepare("UPDATE employee_profiles SET address = ? WHERE user_id = ?");
        }
        $stmt->bind_param("si", $value, $user_id);
    }
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update profile: ' . $conn->error]);
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn->close();
exit();
?>

