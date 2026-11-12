<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';
if (empty($_SESSION['admin_id'])) redirect('login.php');
$pdo=db(); $error=''; $saved=isset($_GET['saved']);
$current=$pdo->query('SELECT setting_key,setting_value FROM payment_settings')->fetchAll(PDO::FETCH_KEY_PAIR);
if ($_SERVER['REQUEST_METHOD']==='POST') {
 csrf_check();
 $values=['jazzcash_number'=>trim((string)($_POST['jazzcash_number']??'')),'easypaisa_number'=>trim((string)($_POST['easypaisa_number']??'')),'account_title'=>trim((string)($_POST['account_title']??''))];
 foreach($values as $v) if (text_length($v)>190) $error='Please keep each field under 190 characters.';
 if (!$error) { $s=$pdo->prepare('INSERT INTO payment_settings(setting_key,setting_value,updated_at) VALUES(?,?,NOW()) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value),updated_at=NOW()'); foreach($values as $k=>$v) $s->execute([$k,$v]); redirect('payment-settings.php?saved=1'); }
 $current=$values;
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Payment settings — Hammad Graphic</title><link rel="stylesheet" href="../assets/css/style.css"></head><body class="admin-dashboard"><main class="admin-container" style="max-width:760px"><div class="admin-title-row"><div><div class="eyebrow">ADMIN SETTINGS</div><h1>Payment <em>details.</em></h1><p>These numbers are shown only on customer payment pages.</p></div><a class="button button-outline" href="index.php">Back to orders</a></div><section class="admin-panel"><?php if($saved): ?><div class="alert alert-success">Payment settings saved.</div><?php endif; ?><?php if($error): ?><div class="alert alert-error"><?=e($error)?></div><?php endif; ?><form method="post" class="admin-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><label>JazzCash wallet / account number<input name="jazzcash_number" maxlength="190" value="<?=e($current['jazzcash_number']??'')?>"></label><label>Easypaisa wallet / account number<input name="easypaisa_number" maxlength="190" value="<?=e($current['easypaisa_number']??'')?>"></label><label>Account holder name<input name="account_title" maxlength="190" value="<?=e($current['account_title']??'')?>"></label><button class="button button-primary">Save payment details</button></form><p class="admin-footnote">Only enter the public receiving account number and account title. Never enter PIN, MPIN, password or OTP.</p></section></main></body></html>