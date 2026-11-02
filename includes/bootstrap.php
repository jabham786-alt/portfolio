<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'lifetime' => 0, 'path' => '/', 'domain' => '',
        'secure' => $isHttps, 'httponly' => true, 'samesite' => 'Lax',
    ]);
    session_start();
}

function e(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function text_length(string $value): int {
    return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
}
function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $bad = ['YOUR_INFINITYFREE_MYSQL_HOST', 'YOUR_DATABASE_NAME', 'YOUR_DATABASE_USERNAME', 'CHANGE_THIS_DATABASE_PASSWORD'];
    foreach ([DB_HOST, DB_NAME, DB_USER, DB_PASS] as $value) {
        if ($value === '' || in_array($value, $bad, true)) {
            throw new RuntimeException('Database is not configured. Update includes/config.php with your hosting MySQL details.');
        }
    }
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return (string)$_SESSION['csrf_token'];
}
function csrf_check(): void {
    $posted = (string)($_POST['csrf_token'] ?? '');
    $stored = (string)($_SESSION['csrf_token'] ?? '');
    if ($posted === '' || $stored === '' || !hash_equals($stored, $posted)) {
        http_response_code(419);
        exit('Your form session expired. Refresh the page and try again.');
    }
}
function redirect(string $path): never {
    header('Location: ' . $path);
    exit;
}
function whatsapp_url(string $message = ''): string {
    $url = 'https://wa.me/' . WHATSAPP_NUMBER;
    return $message !== '' ? $url . '?text=' . rawurlencode($message) : $url;
}
function flash(string $key, ?string $value = null): ?string {
    if ($value !== null) { $_SESSION['_flash'][$key] = $value; return null; }
    $message = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return is_string($message) ? $message : null;
}
