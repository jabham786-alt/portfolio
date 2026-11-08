<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/customer-activation.php';
if (empty($_SESSION['admin_id'])) redirect('login.php');

$error = '';
$notice = flash('customer_admin_notice');
$generated = $_SESSION['customer_activation_once'] ?? null;
unset($_SESSION['customer_activation_once']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $customerId = filter_var($_POST['customer_id'] ?? null, FILTER_VALIDATE_INT);
    $action = (string)($_POST['action'] ?? '');
    if (!$customerId || $customerId < 1) {
        $error = 'Invalid customer record.';
    } else {
        try {
            $pdo = db();
            $pdo->beginTransaction();
            $find = $pdo->prepare('SELECT id,full_name,mobile,account_status FROM customers WHERE id=? FOR UPDATE');
            $find->execute([(int)$customerId]);
            $customer = $find->fetch();
            if (!$customer) throw new RuntimeException('Customer not found.');

            if ($action === 'approve' && $customer['account_status'] === 'pending_admin') {
                $pdo->prepare("UPDATE customers SET account_status='approved_pending_activation',updated_at=NOW() WHERE id=?")->execute([(int)$customerId]);
                $rawToken = customer_create_activation_token($pdo, (int)$customerId);
                $activationUrl = customer_activation_base_url() . '/activate-account.php?token=' . rawurlencode($rawToken);
                $mobile = customer_mobile_digits((string)$customer['mobile']);
                $message = "Assalam-o-Alaikum " . $customer['full_name'] . "! Your Hammad Graphic account has been approved. Activate it using this secure one-time link (valid for 48 hours): " . $activationUrl;
                $waUrl = $mobile !== '' ? 'https://wa.me/' . $mobile . '?text=' . rawurlencode($message) : '';
                $generated = ['name'=>$customer['full_name'],'link'=>$activationUrl,'whatsapp'=>$waUrl,'mobile'=>$customer['mobile']];
                $notice = 'Customer approved. Copy the activation link and send it manually on WhatsApp.';
            } elseif ($action === 'resend' && $customer['account_status'] === 'approved_pending_activation') {
                $rawToken = customer_create_activation_token($pdo, (int)$customerId);
                $activationUrl = customer_activation_base_url() . '/activate-account.php?token=' . rawurlencode($rawToken);
                $mobile = customer_mobile_digits((string)$customer['mobile']);
                $message = "Assalam-o-Alaikum " . $customer['full_name'] . "! Here is your renewed Hammad Graphic account activation link (valid for 48 hours): " . $activationUrl;
                $waUrl = $mobile !== '' ? 'https://wa.me/' . $mobile . '?text=' . rawurlencode($message) : '';
                $generated = ['name'=>$customer['full_name'],'link'=>$activationUrl,'whatsapp'=>$waUrl,'mobile'=>$customer['mobile']];
                $notice = 'A new activation link has been generated. The previous link is now invalid.';
            } elseif ($action === 'reject' && in_array($customer['account_status'], ['pending_admin','approved_pending_activation'], true)) {
                $pdo->prepare("UPDATE customers SET account_status='rejected',updated_at=NOW() WHERE id=?")->execute([(int)$customerId]);
                $pdo->prepare('UPDATE customer_activation_tokens SET consumed_at=NOW() WHERE customer_id=? AND consumed_at IS NULL')->execute([(int)$customerId]);
                $notice = 'Registration rejected. Any unused activation link has been disabled.';
            } else {
                throw new RuntimeException('This action is not available for the customer’s current status.');
            }
            $pdo->commit();
            if ($generated) $_SESSION['customer_activation_once'] = $generated;
            flash('customer_admin_notice', $notice);
            redirect('customers.php');
        } catch (Throwable $e) {
            if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) $pdo->rollBack();
            error_log('Admin customer approval action failed: ' . $e->getMessage());
            $error = $e instanceof RuntimeException ? $e->getMessage() : 'Could not update this customer. Please try again.';
        }
    }
}

$pdo = db();
$customers = $pdo->query('SELECT id,full_name,email,mobile,username,account_status,created_at FROM customers ORDER BY CASE account_status WHEN "pending_admin" THEN 0 WHEN "approved_pending_activation" THEN 1 ELSE 2 END, created_at DESC LIMIT 300')->fetchAll();
$pendingCount = (int)$pdo->query("SELECT COUNT(*) FROM customers WHERE account_status='pending_admin'")->fetchColumn();
?><!doctype html><html lang="en" data-theme="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Customer approvals — Hammad Graphic</title><link rel="stylesheet" href="../assets/css/style.css"></head><body class="admin-dashboard"><header class="admin-topbar"><a class="brand" href="../index.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC</span></span></a><div class="admin-user"><span>Signed in as <strong><?=e($_SESSION['admin_username']??'Admin')?></strong></span><a class="button button-outline" href="index.php">Orders</a><form method="post" action="logout.php"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><button class="logout-button">Log out ↗</button></form></div></header><main class="admin-container"><div class="admin-title-row"><div><div class="eyebrow">CUSTOMER MANAGEMENT</div><h1>Registration <em>approvals.</em></h1><p><?=number_format($pendingCount)?> registration(s) waiting for your review.</p></div><a class="button button-outline" href="../index.php" target="_blank" rel="noopener">View website ↗</a></div><?php if($notice): ?><div class="alert alert-success"><?=e($notice)?></div><?php endif; ?><?php if($error): ?><div class="alert alert-error"><?=e($error)?></div><?php endif; ?>
<?php if($generated): ?><section class="admin-panel"><div class="eyebrow">ACTIVATION LINK READY</div><h2><?=e($generated['name'])?></h2><p>Copy this link and send it manually to the customer's WhatsApp number. It expires in 48 hours and works only once.</p><label>One-time activation link<input id="activationLink" type="text" readonly value="<?=e($generated['link'])?>" style="width:100%"></label><div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:14px"><button class="button button-primary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('activationLink').value).then(()=>this.textContent='Copied ✓')">Copy activation link</button><?php if($generated['whatsapp']): ?><a class="button button-outline" href="<?=e($generated['whatsapp'])?>" target="_blank" rel="noopener">Open WhatsApp message ↗</a><?php endif; ?></div><p class="admin-footnote">WhatsApp will open with a prepared message. You must press Send yourself. The raw link is shown only once in this admin session; generate a new link if you lose it.</p></section><?php endif; ?>
<section class="admin-panel"><div class="admin-panel-heading"><div><div class="eyebrow">CUSTOMER ACCOUNTS</div><h2>All registrations</h2></div><span><?=count($customers)?> records</span></div><div class="table-scroll"><table class="admin-table"><thead><tr><th>Customer</th><th>Contact</th><th>Username</th><th>Status</th><th>Registered</th><th>Action</th></tr></thead><tbody><?php foreach($customers as $c): ?><tr><td><strong><?=e($c['full_name'])?></strong><br><small><?=e($c['email'])?></small></td><td><?=e($c['mobile'])?></td><td><?=e($c['username'])?></td><td><span class="status"><?=e(str_replace('_',' ',$c['account_status']))?></span></td><td><?=e(date('d M Y',strtotime($c['created_at'])))?></td><td><?php if($c['account_status']==='pending_admin'): ?><form method="post" style="display:flex;gap:6px;flex-wrap:wrap"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="customer_id" value="<?=(int)$c['id']?>"><button class="button button-primary" name="action" value="approve">Approve</button><button class="button button-outline" name="action" value="reject" onclick="return confirm('Reject this registration?')">Reject</button></form><?php elseif($c['account_status']==='approved_pending_activation'): ?><form method="post"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="customer_id" value="<?=(int)$c['id']?>"><button class="button button-outline" name="action" value="resend">Generate new link</button></form><?php else: ?>—<?php endif; ?></td></tr><?php endforeach; ?><?php if(!$customers): ?><tr><td colspan="6" class="table-empty">No customer registrations yet.</td></tr><?php endif; ?></tbody></table></div></section><p class="admin-footnote">Approval does not send WhatsApp automatically. You control every message. Existing active customer accounts remain active.</p></main></body></html>