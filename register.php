<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/customer-auth.php';
if (customer_current()) redirect('home.php');
$error = '';
$notice = flash('customer_notice');
$old = $_SESSION['customer_old'] ?? [];
unset($_SESSION['customer_old']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim((string)($_POST['full_name'] ?? ''));
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $mobile = trim((string)($_POST['mobile'] ?? ''));
    $mobileDigits = preg_replace('/\D+/', '', $mobile) ?? '';
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');
    $_SESSION['customer_old'] = ['full_name'=>$name,'email'=>$email,'mobile'=>$mobile,'username'=>$username];
    if ($name === '' || text_length($name) > 100) $error = 'Please enter your name (maximum 100 characters).';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || text_length($email) > 190) $error = 'Please enter a valid email address.';
    elseif (strlen($mobileDigits) < 10 || strlen($mobileDigits) > 15) $error = 'Please enter a valid WhatsApp/mobile number with country code.';
    elseif (!preg_match('/^[A-Za-z0-9_.-]{3,40}$/', $username)) $error = 'Username must be 3–40 characters and use letters, numbers, dot, underscore or hyphen.';
    elseif (strlen($password) < 12 || strlen($password) > 200) $error = 'Password must be at least 12 characters.';
    elseif (!hash_equals($password, $confirm)) $error = 'The two passwords do not match.';
    else {
        try {
            $stmt = db()->prepare("INSERT INTO customers (full_name,email,mobile,username,password_hash,account_status,created_at,updated_at) VALUES (?,?,?,?,?,'pending_admin',NOW(),NOW())");
            $stmt->execute([$name,$email,$mobile,$username,password_hash($password, PASSWORD_DEFAULT)]);
            unset($_SESSION['customer_old']);
            flash('customer_notice', 'Registration received. Your account is pending admin approval. You will receive an activation link manually on WhatsApp after approval.');
            redirect('register.php?submitted=1');
        } catch (PDOException $e) {
            if ((string)$e->getCode() === '23000') $error = 'This email, mobile number or username is already registered. Please log in or contact support.';
            else { error_log('Customer registration error: '.$e->getMessage()); $error = 'Registration could not be completed. Please try again later.'; }
        } catch (Throwable $e) {
            error_log('Customer registration setup error: '.$e->getMessage());
            $error = 'Registration is temporarily unavailable. Please try again later.';
        }
    }
    $_SESSION['customer_old'] = ['full_name'=>$name,'email'=>$email,'mobile'=>$mobile,'username'=>$username];
}
?><!doctype html><html lang="en" data-theme="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Create customer account — Hammad Graphic</title><link rel="stylesheet" href="assets/css/style.css?v=1.0.0"></head><body class="admin-body"><main class="admin-container" style="max-width:620px"><section class="admin-login-card"><a class="brand" href="index.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC</span></span></a><div class="eyebrow" style="margin-top:35px">CUSTOMER ACCOUNT</div><h1>Create your account</h1><p>Register to manage your design orders. Every new account is reviewed by our admin before activation.</p><?php if($notice): ?><div class="alert alert-success"><?=e($notice)?></div><?php endif; ?><?php if($error): ?><div class="alert alert-error"><?=e($error)?></div><?php endif; ?><?php if(!isset($_GET['submitted'])): ?><form method="post" class="admin-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><label>Full name<input name="full_name" maxlength="100" autocomplete="name" required value="<?=e($old['full_name']??'')?>"></label><label>Email address<input type="email" name="email" maxlength="190" autocomplete="email" required value="<?=e($old['email']??'')?>"></label><label>WhatsApp / mobile number<input type="tel" name="mobile" maxlength="30" autocomplete="tel" placeholder="+923001234567" required value="<?=e($old['mobile']??'')?>"></label><label>Username<input name="username" minlength="3" maxlength="40" pattern="[A-Za-z0-9_.-]+" autocomplete="username" required value="<?=e($old['username']??'')?>"></label><label>Password <small>(minimum 12 characters)</small><input type="password" name="password" minlength="12" maxlength="200" autocomplete="new-password" required></label><label>Confirm password<input type="password" name="confirm_password" minlength="12" maxlength="200" autocomplete="new-password" required></label><button class="button button-primary" type="submit">Submit registration ↗</button></form><?php endif; ?><p class="admin-footnote">Already registered? <a href="login.php">Log in</a>. After approval, an activation link will be sent manually to your WhatsApp.</p><p class="admin-footnote"><a href="index.php">Back to website</a></p></section></main></body></html>