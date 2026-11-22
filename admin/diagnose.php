<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';

$results = [];
$error = '';
$ran = false;
$placeholderKey = 'REPLACE_WITH_YOUR_OWN_PRIVATE_RANDOM_KEY_24_CHARS_MIN';
$keyIsSet = INSTALL_KEY !== $placeholderKey && strlen(INSTALL_KEY) >= 24;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $provided = (string)($_POST['install_key'] ?? '');
    if (!$keyIsSet || !hash_equals(INSTALL_KEY, $provided)) {
        $error = 'The private INSTALL_KEY does not match. Update it in includes/config.php and try again.';
    } else {
        $ran = true;
        $dbPasswordPlaceholder = 'PASTE_YOUR_INFINITYFREE_MYSQL_PASSWORD_HERE';
        if (DB_PASS === '' || DB_PASS === $dbPasswordPlaceholder) {
            $results[] = ['fail', 'Database password', 'The placeholder is still in includes/config.php. Enter the actual MySQL password in the live hosting file.'];
        } else {
            try {
                $pdo = db();
                $results[] = ['ok', 'Database connection', 'Connected to the configured MySQL database.'];

                $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_NUM);
                $tableNames = array_map(static fn($row) => (string)$row[0], $tables);
                if (!in_array('orders', $tableNames, true)) {
                    $results[] = ['fail', 'orders table', 'The orders table is missing. Run the one-time installer or import database/schema.sql in phpMyAdmin.'];
                } else {
                    $columns = $pdo->query('SHOW COLUMNS FROM orders')->fetchAll(PDO::FETCH_COLUMN);
                    $required = ['id','order_code','customer_name','email','whatsapp','service','budget','deadline','brief','attachment','status','admin_note','created_at','updated_at'];
                    $missing = array_values(array_diff($required, $columns));
                    $results[] = $missing
                        ? ['fail', 'orders table columns', 'Missing columns: ' . implode(', ', $missing) . '. The imported table does not match the current website schema.']
                        : ['ok', 'orders table structure', 'All required order columns are present.'];
                }

                if (!in_array('admins', $tableNames, true)) {
                    $results[] = ['fail', 'admins table', 'The admins table is missing. Run the one-time installer.'];
                } else {
                    $count = (int)$pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
                    $results[] = ['ok', 'admins table', $count > 0 ? 'An administrator account exists.' : 'The table exists, but no administrator account has been created yet.'];
                }
            } catch (PDOException $exception) {
                $sqlState = (string)($exception->errorInfo[0] ?? $exception->getCode());
                $driverCode = (int)($exception->errorInfo[1] ?? 0);
                $message = 'MySQL connection/query failed (SQLSTATE ' . $sqlState . ', driver code ' . $driverCode . '). ';
                $message .= match ($driverCode) {
                    1045 => 'Check DB_USER and DB_PASS in the live includes/config.php.',
                    1044 => 'The configured database user does not have access to DB_NAME. Recheck DB_NAME and DB_USER.',
                    1049 => 'The DB_NAME value is not recognized. Copy the full database name from InfinityFree.',
                    2002, 2005 => 'Check DB_HOST. Use the MySQL hostname shown in InfinityFree, not localhost.',
                    1146 => 'A required table is missing. Run the one-time installer or import the schema.',
                    default => 'Recheck the MySQL hostname, database name, username, password and table setup.',
                };
                $results[] = ['fail', 'Database connection', $message];
            } catch (Throwable $exception) {
                $results[] = ['fail', 'Database check', 'The check stopped due to a PHP/runtime issue (' . get_class($exception) . '). Check the hosting PHP error log and PHP version.'];
            }
        }

        if (!is_dir(UPLOAD_DIRECTORY)) {
            $results[] = ['fail', 'Upload folder', 'storage/uploads does not exist. Create it in File Manager.'];
        } elseif (!is_writable(UPLOAD_DIRECTORY)) {
            $results[] = ['fail', 'Upload folder', 'storage/uploads exists but PHP cannot write to it. Check the folder permissions in File Manager.'];
        } else {
            $results[] = ['ok', 'Upload folder', 'The folder exists and PHP can write to it.'];
        }

        $results[] = ['info', 'Security reminder', 'Delete admin/diagnose.php from live hosting after completing this check. Do not share your database password or INSTALL_KEY.'];
    }
}
?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Database diagnostics — Hammad Graphic</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
.diag-wrap{width:min(720px,calc(100% - 32px));margin:70px auto}.diag-card{background:var(--surface);border:1px solid var(--line);padding:28px}.diag-result{padding:14px;margin:12px 0;border:1px solid var(--line);background:var(--bg);font-size:12px}.diag-result strong{display:block;margin-bottom:5px}.diag-ok{border-left:4px solid #8bc65a}.diag-fail{border-left:4px solid #df7169}.diag-info{border-left:4px solid #8b7cff}.diag-form{display:flex;gap:10px;flex-wrap:wrap}.diag-form input{flex:1;min-width:220px;background:var(--bg);border:1px solid var(--line);color:var(--text);padding:12px}
</style>
</head>
<body class="admin-body">
<main class="diag-wrap">
<a class="brand" href="../index.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC</span></span></a>
<section class="diag-card">
<div class="eyebrow">PRIVATE DIAGNOSTIC TOOL</div>
<h1 style="font:600 34px var(--display);margin:14px 0">Database <em style="color:var(--accent);font-style:normal">check.</em></h1>
<p style="color:var(--muted);font-size:12px">This tool checks the live MySQL connection, required tables and upload folder. It never displays your database password.</p>
<?php if (!$keyIsSet): ?>
<div class="alert alert-error">Set a private INSTALL_KEY of at least 24 characters in the live includes/config.php first.</div>
<?php elseif (!$ran): ?>
<?php if ($error): ?><div class="alert alert-error"><?=e($error)?></div><?php endif; ?>
<form method="post" class="diag-form">
<input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>">
<input type="password" name="install_key" placeholder="Private INSTALL_KEY" required autocomplete="off">
<button class="button button-primary" type="submit">Run database check</button>
</form>
<?php else: ?>
<?php foreach($results as $result): ?>
<div class="diag-result diag-<?=e($result[0])?>"><strong><?=e($result[1])?></strong><?=e($result[2])?></div>
<?php endforeach; ?>
<?php endif; ?>
</section></main></body></html>
