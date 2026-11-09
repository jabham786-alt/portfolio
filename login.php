<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/customer-auth.php';
if (customer_current()) redirect('home.php');
$error = flash('customer_error');
$next = 'home.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $identity = trim((string)($_POST['identity'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if ($identity === '' || $password === '') $error = 'Enter your username/email and password.';
    else {
        try {
            $pdo = db();
            $ip = (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
            $key = defined('INSTALL_KEY') ? INSTALL_KEY : SITE_NAME;
            $identityHash = hash_hmac('sha256', strtolower($identity), $key);
            $ipHash = hash_hmac('sha256', $ip, $key);
            $limit = $pdo->prepare('SELECT failed_attempts, blocked_until, window_started_at FROM customer_login_attempts WHERE identity_hash=? AND ip_hash=? LIMIT 1');
            $limit->execute([$identityHash, $ipHash]);
            $attempt = $limit->fetch();
            if ($attempt && $attempt['blocked_until'] && strtotime($attempt['blocked_until']) > time()) {
                $error = 'Too many unsuccessful attempts. Please wait 15 minutes and try again.';
            } else {
                $stmt = $pdo->prepare('SELECT id,password_hash,account_status FROM customers WHERE username = ? OR email = ? LIMIT 1');
                $stmt->execute([$identity, strtolower($identity)]);
                $customer = $stmt->fetch();
                if ($customer && password_verify($password, $customer['password_hash'])) {
                    if ($customer['account_status'] === 'pending_admin') $error = 'Your registration is waiting for admin approval. Please check WhatsApp after approval.';
                    elseif ($customer['account_status'] === 'approved_pending_activation') $error = 'Your account is approved. Open the activation link sent to your WhatsApp to activate it.';
                    elseif ($customer['account_status'] === 'rejected') $error = 'This registration was not approved. Please contact us on WhatsApp.';
                    elseif ($customer['account_status'] === 'suspended') $error = 'This account is currently suspended. Please contact support.';
                    elseif ($customer['account_status'] === 'active') {
                        if (password_needs_rehash($customer['password_hash'], PASSWORD_DEFAULT)) {
                            $update = $pdo->prepare('UPDATE customers SET password_hash=? WHERE id=?');
                            $update->execute([password_hash($password, PASSWORD_DEFAULT),(int)$customer['id']]);
                        }
                        $clear = $pdo->prepare('DELETE FROM customer_login_attempts WHERE identity_hash=? AND ip_hash=?');
                        $clear->execute([$identityHash,$ipHash]);
                        customer_login((int)$customer['id']);
                        redirect('home.php');
                    }
                } else {
                    $record = $pdo->prepare("INSERT INTO customer_login_attempts (identity_hash,ip_hash,failed_attempts,window_started_at,blocked_until) VALUES (?,?,1,NOW(),NULL) ON DUPLICATE KEY UPDATE failed_attempts=IF(window_started_at < DATE_SUB(NOW(), INTERVAL 15 MINUTE),1,failed_attempts+1), window_started_at=IF(window_started_at < DATE_SUB(NOW(), INTERVAL 15 MINUTE),NOW(),window_started_at), blocked_until=IF(IF(window_started_at < DATE_SUB(NOW(), INTERVAL 15 MINUTE),1,failed_attempts+1)>=8,DATE_ADD(NOW(), INTERVAL 15 MINUTE),NULL)");
                    $record->execute([$identityHash,$ipHash]);
                    usleep(350000);
                    $error = 'Login details are incorrect.';
                }
            }
        } catch (Throwable $e) {
            error_log('Customer login error: '.$e->getMessage());
            $error = 'Login is temporarily unavailable. Please try again later.';
        }
    }
}
?><!doctype html><html lang="en" data-theme="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Customer login — Hammad Graphic</title><link rel="stylesheet" href="assets/css/style.css?v=1.0.0"></head><body class="admin-body"><main class="admin-container" style="max-width:540px"><section class="admin-login-card"><a class="brand" href="index.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC</span></span></a><div class="eyebrow" style="margin-top:35px">CUSTOMER PORTAL</div><h1>Welcome back.</h1><p>Log in to see your orders and payment information.</p><?php if($error): ?><div class="alert alert-error"><?=e($error)?></div><?php endif; ?><form method="post" class="admin-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="next" value="<?=e($next)?>"><label>Username or email<input name="identity" autocomplete="username" required></label><label>Password<input type="password" name="password" autocomplete="current-password" required></label><button class="button button-primary" type="submit">Log in ↗</button></form><p class="admin-footnote">New here? <a href="register.php">Create an account</a>.</p><p class="admin-footnote"><a href="index.php">Back to website</a> · <a href="admin/login.php">Admin login</a></p></section></main></body></html>