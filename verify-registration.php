<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/customer-auth.php';
$customerId = (int)($_SESSION['pending_customer_verification'] ?? 0);
if ($customerId < 1) redirect('register.php');
$error = '';
$notice = flash('customer_otp_notice');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $otp = trim((string)($_POST['otp'] ?? ''));
    if (!preg_match('/^[0-9]{6}$/', $otp)) {
        $error = 'Please enter the 6-digit code sent to your WhatsApp.';
    } else {
        try {
            $pdo = db();
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('SELECT otp_hash, (expires_at > NOW()) AS is_valid, attempts FROM customer_registration_otps WHERE customer_id=? FOR UPDATE');
            $stmt->execute([$customerId]);
            $record = $stmt->fetch();
            if (!$record || (int)$record['is_valid'] !== 1) {
                $pdo->rollBack();
                $error = 'Your code has expired. Request a new code.';
            } elseif ((int)$record['attempts'] >= 5) {
                $pdo->rollBack();
                $error = 'Too many incorrect attempts. Request a new code.';
            } elseif (!password_verify($otp, $record['otp_hash'])) {
                $bad = $pdo->prepare('UPDATE customer_registration_otps SET attempts=attempts+1 WHERE customer_id=?');
                $bad->execute([$customerId]);
                $pdo->commit();
                $error = 'The code is incorrect. Please try again.';
            } else {
                $activate = $pdo->prepare("UPDATE customers SET account_status='active', updated_at=NOW() WHERE id=? AND account_status='pending_verification'");
                $activate->execute([$customerId]);
                $del = $pdo->prepare('DELETE FROM customer_registration_otps WHERE customer_id=?');
                $del->execute([$customerId]);
                $pdo->commit();
                unset($_SESSION['pending_customer_verification'], $_SESSION['customer_old']);
                customer_login($customerId);
                redirect('customer/index.php?welcome=1');
            }
        } catch (Throwable $e) {
            if (db()->inTransaction()) db()->rollBack();
            error_log('Customer WhatsApp OTP verification error: ' . $e->getMessage());
            $error = 'Verification is temporarily unavailable. Please try again.';
        }
    }
}
?><!doctype html><html lang="en" data-theme="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Verify WhatsApp — Hammad Graphic</title><link rel="stylesheet" href="assets/css/style.css?v=1.0.0"></head><body class="admin-body"><main class="admin-container" style="max-width:540px"><section class="admin-login-card"><div class="eyebrow">WHATSAPP VERIFICATION</div><h1>Enter your OTP</h1><p>We sent a 6-digit registration code to your WhatsApp number. It expires in 5 minutes.</p><?php if($notice): ?><div class="alert"><?=e($notice)?></div><?php endif; ?><?php if($error): ?><div class="alert alert-error"><?=e($error)?></div><?php endif; ?><form method="post" class="admin-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><label>6-digit OTP<input name="otp" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" minlength="6" maxlength="6" required autofocus></label><button class="button button-primary" type="submit">Verify and activate account</button></form><form method="post" action="resend-registration-otp.php" style="margin-top:14px"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><button class="button" type="submit">Resend WhatsApp code</button></form><p class="admin-footnote"><a href="register.php">Back to registration</a></p></section></main></body></html>