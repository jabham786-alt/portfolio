<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/whatsapp-otp.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('verify-registration.php');
csrf_check();
$customerId = (int)($_SESSION['pending_customer_verification'] ?? 0);
if ($customerId < 1) redirect('register.php');
try {
    $stmt = db()->prepare("SELECT mobile FROM customers WHERE id=? AND account_status='pending_verification' LIMIT 1");
    $stmt->execute([$customerId]);
    $customer = $stmt->fetch();
    if (!$customer) redirect('register.php');
    customer_issue_whatsapp_otp($customerId, (string)$customer['mobile']);
    flash('customer_otp_notice', 'A new WhatsApp code has been sent. It is valid for 5 minutes.');
} catch (Throwable $e) {
    error_log('WhatsApp OTP resend error: ' . $e->getMessage());
    flash('customer_otp_notice', 'Could not send a new code yet. Wait 60 seconds and try again, or check WhatsApp setup.');
}
redirect('verify-registration.php');
