<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/customer-auth.php';
require_once __DIR__ . '/includes/whatsapp-otp.php';
if (customer_current()) redirect('customer/index.php');
$error = flash('customer_error');
$old = $_SESSION['customer_old'] ?? [];
unset($_SESSION['customer_old']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim((string)($_POST['full_name'] ?? ''));
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $mobileInput = trim((string)($_POST['mobile'] ?? ''));
    try { $mobile = wa_normalize_mobile($mobileInput); } catch (InvalidArgumentException $mobileError) { $mobile = ''; }
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');
    $_SESSION['customer_old'] = ['full_name'=>$name,'email'=>$email,'mobile'=>$mobileInput,'username'=>$username];
    if (empty($_POST['whatsapp_consent'])) $error = 'Please agree to receive a one-time registration verification code on WhatsApp.';
    elseif ($name === '' || text_length($name) > 100) $error = 'Please enter your name (maximum 100 characters).';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || text_length($email) > 190) $error = 'Please enter a valid email address.';
    elseif ($mobile === '') $error = 'Please enter a valid WhatsApp number with country code, e.g. +923001234567.';
    elseif (!preg_match('/^[A-Za-z0-9_.-]{3,40}$/', $username)) $error = 'Username must be 3–40 characters and use letters, numbers, dot, underscore or hyphen.';
    elseif (strlen($password) < 12 || strlen($password) > 200) $error = 'Password must be at least 12 characters.';
    elseif (!hash_equals($password, $confirm)) $error = 'The two passwords do not match.';
    else {
        try {
            $stmt = db()->prepare("INSERT INTO customers (full_name,email,mobile,username,password_hash,account_status,created_at,updated_at) VALUES (?,?,?,?,?,'pending_verification',NOW(),NOW())");
            $stmt->execute([$name,$email,$mobile,$username,password_hash($password, PASSWORD_DEFAULT)]);
            $id = (int)db()->lastInsertId();
            $_SESSION['pending_customer_verification'] = $id;
            unset($_SESSION['customer_old']);
            try {
                customer_issue_whatsapp_otp($id, $mobile);
                flash('customer_otp_notice', 'Your WhatsApp verification code has been sent. It expires in 5 minutes.');
            } catch (Throwable $sendError) {
                error_log('Initial WhatsApp OTP send failed for pending customer ID ' . $id . ': ' . $sendError->getMessage());
                flash('customer_otp_notice', 'Your account is saved but the OTP could not be sent. Check the WhatsApp configuration and use Resend code.');
            }
            redirect('verify-registration.php');
        } catch (PDOException $e) {
            if ((string)$e->getCode() === '23000') $error = 'This email, mobile number or username is already registered. Please log in or contact support if you did not finish verification.';
            else { error_log('Customer registration error: '.$e->getMessage()); $error = 'Registration could not be completed. Please try again later.'; }
        } catch (Throwable $e) {
            error_log('Customer registration setup error: '.$e->getMessage());
            $error = 'Registration is temporarily unavailable. Please try again later.';
        }
    }
    $_SESSION['customer_old'] = ['full_name'=>$name,'email'=>$email,'mobile'=>$mobileInput,'username'=>$username];
}
?><!doctype html><html lang="en" data-theme="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Create customer account — Hammad Graphic</title><link rel="stylesheet" href="assets/css/style.css?v=1.0.0"></head><body class="admin-body"><main class="admin-container" style="max-width:620px"><section class="admin-login-card"><a class="brand" href="index.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC</span></span></a><div class="eyebrow" style="margin-top:35px">CUSTOMER ACCOUNT</div><h1>Create your account</h1><p>Register once to manage your design orders. We will verify your WhatsApp number with a one-time code.</p><?php if($error): ?><div class="alert alert-error"><?=e($error)?></div><?php endif; ?><form method="post" class="admin-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><label>Full name<input name="full_name" maxlength="100" autocomplete="name" required value="<?=e($old['full_name']??'')?>"></label><label>Email address<input type="email" name="email" maxlength="190" autocomplete="email" required value="<?=e($old['email']??'')?>"></label><label>WhatsApp number<input type="tel" name="mobile" maxlength="30" autocomplete="tel" placeholder="+923001234567" required value="<?=e($old['mobile']??'')?>"></label><label>Username<input name="username" minlength="3" maxlength="40" pattern="[A-Za-z0-9_.-]+" autocomplete="username" required value="<?=e($old['username']??'')?>"></label><label>Password <small>(minimum 12 characters)</small><input type="password" name="password" minlength="12" maxlength="200" autocomplete="new-password" required></label><label>Confirm password<input type="password" name="confirm_password" minlength="12" maxlength="200" autocomplete="new-password" required></label><label style="display:flex;gap:10px;align-items:flex-start"><input type="checkbox" name="whatsapp_consent" value="1" required style="width:auto;margin-top:4px">I agree to receive a one-time WhatsApp verification code on this number.</label><button class="button button-primary" type="submit">Continue to WhatsApp verification ↗</button></form><p class="admin-footnote">Already registered? <a href="login.php">Log in</a>. Never share your OTP or password.</p></section></main></body></html>