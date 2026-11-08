<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/customer-auth.php';
if (customer_current()) redirect('customer/index.php');
$error = flash('customer_error');
$next = (string)($_GET['next'] ?? $_POST['next'] ?? 'customer/index.php');
$allowedNext = ['customer/index.php','customer/orders.php','order.php'];
if (!in_array($next, $allowedNext, true)) $next = 'customer/index.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $identity = trim((string)($_POST['identity'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if ($identity === '' || $password === '') $error = 'Enter your username/email and password.';
    else {
        try {
            $stmt = db()->prepare('SELECT id,password_hash,account_status FROM customers WHERE username = ? OR email = ? LIMIT 1');
            $stmt->execute([$identity, strtolower($identity)]);
            $customer = $stmt->fetch();
            if ($customer && $customer['account_status'] === 'active' && password_verify($password, $customer['password_hash'])) {
                if (password_needs_rehash($customer['password_hash'], PASSWORD_DEFAULT)) {
                    $update = db()->prepare('UPDATE customers SET password_hash=? WHERE id=?');
                    $update->execute([password_hash($password, PASSWORD_DEFAULT),(int)$customer['id']]);
                }
                customer_login((int)$customer['id']);
                redirect($next);
            }
            usleep(350000);
            $error = 'Login details are incorrect or the account is not active.';
        } catch (Throwable $e) {
            error_log('Customer login error: '.$e->getMessage());
            $error = 'Login is temporarily unavailable. Please try again later.';
        }
    }
}
?><!doctype html><html lang="en" data-theme="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Customer login — Hammad Graphic</title><link rel="stylesheet" href="assets/css/style.css?v=1.0.0"></head><body class="admin-body"><main class="admin-container" style="max-width:540px"><section class="admin-login-card"><a class="brand" href="index.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC</span></span></a><div class="eyebrow" style="margin-top:35px">CUSTOMER PORTAL</div><h1>Welcome back.</h1><p>Log in to see your orders and payment information.</p><?php if($error): ?><div class="alert alert-error"><?=e($error)?></div><?php endif; ?><form method="post" class="admin-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="next" value="<?=e($next)?>"><label>Username or email<input name="identity" autocomplete="username" required></label><label>Password<input type="password" name="password" autocomplete="current-password" required></label><button class="button button-primary" type="submit">Log in ↗</button></form><p class="admin-footnote">New here? <a href="register.php">Create an account</a>.</p><p class="admin-footnote"><a href="index.php">Back to website</a> · <a href="admin/login.php">Admin login</a></p></section></main></body></html>