<?php


require_once '../includes/auth_check.php';
require_once '../config/db.php';


date_default_timezone_set('Asia/Kolkata');

$page_title = 'My Payroll';


$stmt = $conn->prepare("SELECT basic_salary, allowances, deductions, net_salary, updated_at FROM payroll WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$payroll = $stmt->get_result()->fetch_assoc();
$stmt->close();


if ($payroll) {
    $payroll['basic_salary'] = floatval($payroll['basic_salary'] ?? 0);
    $payroll['allowances'] = floatval($payroll['allowances'] ?? 0);
    $payroll['deductions'] = floatval($payroll['deductions'] ?? 0);
    $payroll['net_salary'] = floatval($payroll['net_salary'] ?? 0);
}

include '../includes/header.php';
?>

<h2>My Payroll</h2>

<div class="payroll-container">
    <div class="payroll-section">
        <h3>Salary Information</h3>
        <?php if ($payroll): ?>
            <table class="info-table">
                <tr>
                    <th>Basic Salary:</th>
                    <td>₹<?php echo number_format($payroll['basic_salary'], 2); ?></td>
                </tr>
                <tr>
                    <th>Allowances:</th>
                    <td>₹<?php echo number_format($payroll['allowances'], 2); ?></td>
                </tr>
                <tr>
                    <th>Deductions:</th>
                    <td>₹<?php echo number_format($payroll['deductions'], 2); ?></td>
                </tr>
                <tr class="total-row">
                    <th>Net Salary:</th>
                    <td><strong>₹<?php echo number_format($payroll['net_salary'], 2); ?></strong></td>
                </tr>
                <?php if ($payroll['updated_at']): ?>
                    <tr>
                        <th>Last Updated:</th>
                        <td><?php echo date('M d, Y H:i', strtotime($payroll['updated_at'])); ?></td>
                    </tr>
                <?php endif; ?>
            </table>
            <p class="info-note">* Salary information is read-only. Contact HR for any queries.</p>
        <?php else: ?>
            <p>No payroll information available.</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

