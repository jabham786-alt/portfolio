<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';

/*
 * TEMPORARY ADMIN PASSWORD RESET TOOL
 * Requires the private INSTALL_KEY stored in the live includes/config.php.
 * Delete this file from the server immediately after a successful reset.
 */

$expectedPlaceholder = 'REPLACE_WITH_YOUR_OWN_PRIVATE_RANDOM_KEY_24_CHARS_MIN';
$keyConfigured = INSTALL_KEY !== $expectedPlaceholder && strlen(INSTALL_KEY) >= 24;
$message = '';
$messageType = 'error';
$success = false;

$attempts = $_SESSION['admin_reset_attempts'] ?? ['count' => 0, 'since' => time()];
if (!is_array($attempts) || time() - (int)($attempts['since'] ?? 0) > 900) {
    $attempts = ['count' => 0, 'since' => time()];
}
$locked = (int)($attempts['count'] ?? 0) >= 5;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    if (!$keyConfigured) {
        $message = 'The private INSTALL_KEY is not configured. Set it in the live includes/config.php first.';
    } elseif ($locked) {
        $message = 'Too many reset attempts. Wait 15 minutes before trying again.';
    } else {
        $providedKey = (string)($_POST['install_key'] ?? '');
        if (!hash_equals(INSTALL_KEY, $providedKey)) {
            $attempts['count'] = (int)$attempts['count'] + 1;
            $_SESSION['admin_reset_attempts'] = $attempts;
            $message = 'The installation key is incorrect.';
        } else {
            $username = trim((string)($_POST['username'] ?? ''));
            $newPassword = (string)($_POST['new_password'] ?? '');
            $confirmPassword = (string)($_POST['confirm_password'] ?? '');

            if (!preg_match('/^[A-Za-z0-9_.-]{3,40}$/', $username)) {
                $message = 'Enter the exact admin username (3–40 letters, numbers, dots, dashes or underscores).';
            } elseif (strlen($newPassword) < 12) {
                $message = 'The new password must contain at least 12 characters.';
            } elseif (!hash_equals($newPassword, $confirmPassword)) {
                $message = 'The new password and confirmation do not match.';
            } else {
                try {
                    $pdo = db();
                    $lookup = $pdo->prepare('SELECT id FROM admins WHERE username = ? LIMIT 1');
                    $lookup->execute([$username]);
                    $admin = $lookup->fetch();

                    if (!$admin) {
                        $message = 'No admin account with that username was found. Check the username and try again.';
                    } else {
                        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                        $update = $pdo->prepare('UPDATE admins SET password_hash = ?, last_login = NULL WHERE id = ?');
                        $update->execute([$hash, (int)$admin['id']]);

                        if ($update->rowCount() !== 1) {
                            // Updating to an identical hash is unlikely, but verify the stored hash.
                            $verify = $pdo->prepare('SELECT password_hash FROM admins WHERE id = ? LIMIT 1');
                            $verify->execute([(int)$admin['id']]);
                            $stored = $verify->fetchColumn();
                            if (!is_string($stored) || !password_verify($newPassword, $stored)) {
                                throw new RuntimeException('The password update could not be verified.');
                            }
                        }

                        unset($_SESSION['admin_reset_attempts']);
                        session_regenerate_id(true);
                        $success = true;
                        $messageType = 'success';
                        $message = 'Admin password reset successfully. Delete admin/reset-password.php from your hosting File Manager now, then sign in with the new password.';
                    }
                } catch (Throwable $exception) {
                    error_log('Hammad Graphic admin password reset error: ' . $exception->getMessage());
                    $message = 'Password reset could not be completed. Check the live database settings and admins table, then try again.';
                }
            }
        }
    }
}

$csrf = e(csrf_token());
?><!doctype html>
<html lang="en" data-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin password reset — Hammad Graphic</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
.reset-wrap{width:min(520px,calc(100% - 32px));margin:60px auto}
.reset-card{background:var(--surface);border:1px solid var(--line);padding:30px}
.reset-card h1{font:600 34px/1.1 var(--display);margin:14px 0}
.reset-card h1 em{font-style:normal;color:var(--accent)}
.reset-card p,.reset-card .hint{font-size:12px;line-height:1.8;color:var(--muted)}
.reset-form{display:flex;flex-direction:column;gap:16px;margin-top:20px}
.reset-form label{display:flex;flex-direction:column;gap:7px;font-size:11px;font-weight:700}
.reset-form input{background:var(--bg);border:1px solid var(--line);color:var(--text);padding:12px;width:100%}
.reset-warning{border:1px solid #d6a84c;background:rgba(214,168,76,.08);color:var(--text);padding:12px;font-size:11px;line-height:1.7;margin-top:18px}
.reset-success{border-color:#8bc65a;color:var(--accent)}
</style>
</head>
<body class="admin-body">
<main class="reset-wrap">
<a class="brand" href="../index.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC</span></span></a>
<section class="reset-card">
<div class="eyebrow">TEMPORARY ACCOUNT RECOVERY</div>
<h1>Reset admin<br><em>password.</em></h1>
<p>This page changes the password for an existing admin account. It does not reveal the old password or your database password.</p>
<?php if (!$keyConfigured): ?>
<div class="alert alert-error">The live INSTALL_KEY is still the public placeholder. Set your own private key in includes/config.php before using this page.</div>
<?php elseif ($message !== ''): ?>
<div class="alert <?= $messageType === 'success' ? 'alert-success' : 'alert-error' ?>"><?= e($message) ?></div>
<?php endif; ?>
<?php if (!$success && $keyConfigured): ?>
<form method="post" class="reset-form" autocomplete="off">
<input type="hidden" name="csrf_token" value="<?= $csrf ?>">
<label>Private INSTALL_KEY
<input type="password" name="install_key" required autocomplete="off">
</label>
<label>Exact admin username
<input type="text" name="username" required minlength="3" maxlength="40" pattern="[A-Za-z0-9_.-]{3,40}" autocomplete="username">
</label>
<label>New password (at least 12 characters)
<input type="password" name="new_password" required minlength="12" autocomplete="new-password">
</label>
<label>Confirm new password
<input type="password" name="confirm_password" required minlength="12" autocomplete="new-password">
</label>
<button class="button button-primary" type="submit">Reset admin password</button>
</form>
<?php endif; ?>
<div class="reset-warning"><strong>Security:</strong> Use this page only over HTTPS. After the reset succeeds, delete <code>admin/reset-password.php</code> from InfinityFree File Manager immediately. Never share the INSTALL_KEY or your new admin password.</div>
<a class="admin-back-link" href="login.php">← Back to admin login</a>
</section>
</main>
</body>
</html>
