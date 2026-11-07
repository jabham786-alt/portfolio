<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
$token = strtolower(trim((string)($_GET['token'] ?? '')));
if (!preg_match('/^[a-f0-9]{64}$/', $token)) { http_response_code(404); exit('Payment link not found.'); }
$pdo = db();
$q = $pdo->prepare('SELECT * FROM orders WHERE payment_token = ? LIMIT 1');
$q->execute([$token]); $order = $q->fetch();
if (!$order || $order['quote_amount'] === null) { http_response_code(404); exit('Payment link is not available.'); }
$settings = $pdo->query('SELECT setting_key, setting_value FROM payment_settings')->fetchAll(PDO::FETCH_KEY_PAIR);
$error = flash('payment_error'); $success = isset($_GET['submitted']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 csrf_check();
 if ($order['payment_status'] === 'paid') { $error = 'This order is already marked as paid.'; }
 else {
  $method = (string)($_POST['payment_method'] ?? '');
  $reference = trim((string)($_POST['payment_reference'] ?? ''));
  if (!in_array($method, ['jazzcash','easypaisa'], true) || $reference === '' || text_length($reference) > 120) $error = 'Please select a payment method and enter the transaction/reference ID.';
  elseif (empty($_FILES['proof']) || (int)$_FILES['proof']['error'] !== UPLOAD_ERR_OK || (int)$_FILES['proof']['size'] < 1 || (int)$_FILES['proof']['size'] > MAX_UPLOAD_BYTES) $error = 'Please upload a receipt image or PDF smaller than 4 MB.';
  else {
   $file = $_FILES['proof']; $mime = (new finfo(FILEINFO_MIME_TYPE))->file((string)$file['tmp_name']);
   $types = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','application/pdf'=>'pdf'];
   if (!isset($types[$mime])) $error = 'Only JPG, PNG, WEBP and PDF receipts are accepted.';
   else {
    if (!is_dir(UPLOAD_DIRECTORY) && !mkdir(UPLOAD_DIRECTORY,0750,true) && !is_dir(UPLOAD_DIRECTORY)) $error = 'Receipt storage is unavailable. Contact the website owner.';
    else {
     $stored = 'payment-'.bin2hex(random_bytes(18)).'.'.$types[$mime];
     if (!move_uploaded_file((string)$file['tmp_name'], UPLOAD_DIRECTORY.'/'.$stored)) $error = 'Could not save the receipt. Please try again.';
     else {
      $u = $pdo->prepare("UPDATE orders SET payment_method=?, payment_reference=?, payment_proof=?, payment_status='pending_verification', updated_at=NOW() WHERE id=? AND payment_status<>'paid'");
      $u->execute([$method,$reference,$stored,(int)$order['id']]);
      header('Location: payment.php?token='.rawurlencode($token).'&submitted=1'); exit;
     }
    }
   }
  }
 }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Pay order <?=e($order['order_code'])?> — Hammad Graphic</title><link rel="stylesheet" href="assets/css/style.css"></head><body class="admin-dashboard"><main class="admin-container" style="max-width:760px"><section class="admin-panel"><div class="eyebrow">HAMMAD GRAPHIC · PAYMENT</div><h1>Order <?=e($order['order_code'])?></h1><p>Customer: <?=e($order['customer_name'])?></p><h2>Amount due: PKR <?=e(number_format((float)$order['quote_amount'],2))?></h2>
<?php if($order['payment_status']==='paid'): ?><div class="alert alert-success">Payment has been confirmed by Hammad Graphic. Thank you.</div>
<?php elseif($success || $order['payment_status']==='pending_verification'): ?><div class="alert alert-success">Your payment details were submitted. Status: Pending verification. Your order will be marked paid only after the payment is checked.</div>
<?php else: ?><?php if($error): ?><div class="alert alert-error"><?=e($error)?></div><?php endif; ?>
<p>Select a wallet and send the exact amount to the number shown. Then enter the transaction ID and upload your receipt.</p>
<form method="post" enctype="multipart/form-data" class="admin-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>">
<label>Payment method<select name="payment_method" required><option value="">Choose wallet</option><option value="jazzcash">JazzCash</option><option value="easypaisa">Easypaisa</option></select></label>
<div class="brief-box"><strong>JazzCash</strong><p><?=e($settings['jazzcash_number']??'Not configured yet')?></p><strong>Easypaisa</strong><p><?=e($settings['easypaisa_number']??'Not configured yet')?></p><small>Account title: <?=e($settings['account_title']??'Not configured yet')?></small></div>
<label>Transaction / reference ID<input name="payment_reference" maxlength="120" required></label>
<label>Upload receipt (JPG, PNG, WEBP or PDF; max 4 MB)<input type="file" name="proof" accept=".jpg,.jpeg,.png,.webp,.pdf" required></label>
<button class="button button-primary" type="submit">Submit payment details</button></form><?php endif; ?>
<p class="admin-footnote">Do not send your PIN, MPIN, password or OTP. This page does not collect banking credentials.</p></section></main><?php require __DIR__.'/includes/analytics-snippet.php'; ?>
</body></html>