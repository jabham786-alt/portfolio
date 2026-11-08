<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/customer-auth.php';
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Referrer-Policy: no-referrer');
$token = trim((string)($_GET['token'] ?? $_POST['token'] ?? ''));
$error = '';
$valid = false;
if (preg_match('/^[a-f0-9]{64}$/', $token)) {
    try {
        $check = db()->prepare("SELECT t.customer_id FROM customer_activation_tokens t INNER JOIN customers c ON c.id=t.customer_id WHERE t.token_hash=? AND t.consumed_at IS NULL AND t.expires_at>NOW() AND c.account_status='approved_pending_activation' LIMIT 1");
        $check->execute([hash('sha256', $token)]);
        $valid = (bool)$check->fetch();
    } catch (Throwable $e) {
        error_log('Activation link lookup failed: '.$e->getMessage());
        $error = 'Activation is temporarily unavailable. Please try again later.';
    }
} else {
    $error = 'This activation link is invalid or incomplete.';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid) {
    csrf_check();
    try {
        $pdo = db();
        $pdo->beginTransaction();
        $lock = $pdo->prepare("SELECT t.id,t.customer_id FROM customer_activation_tokens t INNER JOIN customers c ON c.id=t.customer_id WHERE t.token_hash=? AND t.consumed_at IS NULL AND t.expires_at>NOW() AND c.account_status='approved_pending_activation' FOR UPDATE");
        $lock->execute([hash('sha256', $token)]);
        $row = $lock->fetch();
        if (!$row) {
            $pdo->rollBack();
            $valid = false;
            $error = 'This activation link has expired or has already been used. Ask the admin for a new link.';
        } else {
            $activate = $pdo->prepare("UPDATE customers SET account_status='active',updated_at=NOW() WHERE id=? AND account_status='approved_pending_activation'");
            $activate->execute([(int)$row['customer_id']]);
            if ($activate->rowCount() !== 1) throw new RuntimeException('Account status changed before activation.');
            $consume = $pdo->prepare('UPDATE customer_activation_tokens SET consumed_at=NOW() WHERE id=? AND consumed_at IS NULL');
            $consume->execute([(int)$row['id']]);
            $pdo->commit();
            customer_login((int)$row['customer_id']);
            redirect('customer/index.php?welcome=1');
        }
    } catch (Throwable $e) {
        if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) $pdo->rollBack();
        error_log('Customer activation failed: '.$e->getMessage());
        $error = 'Could not activate your account. Please ask the admin for a new activation link.';
    }
}
?><!doctype html><html lang="en" data-theme="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Activate account — Hammad Graphic</title><link rel="stylesheet" href="assets/css/style.css?v=1.0.0"></head><body class="admin-body"><main class="admin-container" style="max-width:560px"><section class="admin-login-card"><a class="brand" href="index.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC</span></span></a><div class="eyebrow" style="margin-top:35px">ACCOUNT ACTIVATION</div><h1>Activate your account.</h1><?php if($valid): ?><p>Your registration has been approved by Hammad Graphic. Press the button below to activate your account and sign in.</p><form method="post" class="admin-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="token" value="<?=e($token)?>"><button class="button button-primary" type="submit">Activate my account ↗</button></form><?php else: ?><div class="alert alert-error"><?=e($error ?: 'This activation link is no longer valid.')?></div><p>Please contact Hammad Graphic on WhatsApp and request a fresh activation link.</p><a class="button button-primary" href="<?=e(whatsapp_url('Assalam-o-Alaikum, please send me a new Hammad Graphic account activation link.'))?>" target="_blank" rel="noopener">Contact on WhatsApp ↗</a><?php endif; ?><p class="admin-footnote"><a href="index.php">Back to website</a></p></section></main></body></html>