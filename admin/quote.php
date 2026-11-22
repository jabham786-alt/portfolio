<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';
if (empty($_SESSION['admin_id'])) redirect('login.php');
$pdo=db(); $id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if(!$id){http_response_code(404);exit('Order not found.');}
$q=$pdo->prepare('SELECT * FROM orders WHERE id=?');$q->execute([$id]);$o=$q->fetch();
if(!$o){http_response_code(404);exit('Order not found.');}
$error='';$saved=isset($_GET['saved']);
if($_SERVER['REQUEST_METHOD']==='POST'){
 csrf_check();
 if(isset($_POST['save_quote'])){
  $amount=(string)($_POST['quote_amount']??'');
  if(!is_numeric($amount)||(float)$amount<=0||(float)$amount>99999999){$error='Enter a valid amount in PKR.';}
  else{$token=$o['payment_token']?:bin2hex(random_bytes(32));$u=$pdo->prepare('UPDATE orders SET quote_amount=?,payment_token=?,updated_at=NOW() WHERE id=?');$u->execute([(float)$amount,$token,$id]);redirect('quote.php?id='.$id.'&saved=1');}
 }
 if(isset($_POST['verify_payment'])){
  $decision=(string)($_POST['decision']??'');
  if($decision==='paid'){$u=$pdo->prepare("UPDATE orders SET payment_status='paid',paid_at=NOW(),updated_at=NOW() WHERE id=? AND payment_status='pending_verification'");$u->execute([$id]);}
  elseif($decision==='reject'){$u=$pdo->prepare("UPDATE orders SET payment_status='rejected',updated_at=NOW() WHERE id=? AND payment_status='pending_verification'");$u->execute([$id]);}
  redirect('quote.php?id='.$id);
 }
 $q->execute([$id]);$o=$q->fetch();
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Quote and payment — Hammad Graphic</title><link rel="stylesheet" href="../assets/css/style.css"></head><body class="admin-dashboard"><main class="admin-container" style="max-width:760px"><div class="admin-title-row"><div><div class="eyebrow">ORDER PAYMENT</div><h1><?=e($o['order_code'])?></h1><p><?=e($o['customer_name'])?></p></div><a class="button button-outline" href="order.php?id=<?=$id?>">Back to order</a></div><section class="admin-panel"><?php if($saved):?><div class="alert alert-success">Quote saved. Share the customer payment link below.</div><?php endif;?><?php if($error):?><div class="alert alert-error"><?=e($error)?></div><?php endif;?><h2>Set quote amount</h2><form method="post" class="admin-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="save_quote" value="1"><label>Amount in PKR<input type="number" min="1" step="0.01" name="quote_amount" required value="<?=e($o['quote_amount']??'')?>"></label><button class="button button-primary">Save quote and generate link</button></form><?php if(!empty($o['payment_token'])):?><div class="brief-box"><strong>Customer payment link</strong><p><a href="../payment.php?token=<?=e($o['payment_token'])?>" target="_blank">Open payment page</a></p><p>Payment status: <?=e($o['payment_status']??'unpaid')?></p><p>Method: <?=e($o['payment_method']??'Not selected')?> · Reference: <?=e($o['payment_reference']??'Not submitted')?></p><?php if(!empty($o['payment_proof'])):?><p><a href="payment-proof.php?id=<?=$id?>">View uploaded receipt</a></p><?php endif;?></div><?php endif;?><?php if(($o['payment_status']??'')==='pending_verification'):?><h2>Verify customer payment</h2><form method="post" class="admin-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="verify_payment" value="1"><label>Decision<select name="decision"><option value="paid">Confirm money received</option><option value="reject">Reject receipt</option></select></label><button class="button button-primary">Save payment decision</button></form><?php endif;?></section></main></body></html>