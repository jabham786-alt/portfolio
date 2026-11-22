<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false]);
    exit;
}
$origin = (string)($_SERVER['HTTP_ORIGIN'] ?? '');
$host = (string)($_SERVER['HTTP_HOST'] ?? '');
if ($origin !== '' && !hash_equals(strtolower($host), strtolower((string)parse_url($origin, PHP_URL_HOST)))) {
    http_response_code(403);
    echo json_encode(['ok' => false]);
    exit;
}
$raw = file_get_contents('php://input');
$data = json_decode(is_string($raw) ? $raw : '', true);
$type = is_array($data) ? (string)($data['event'] ?? '') : '';
$visitor = is_array($data) ? (string)($data['visitor'] ?? '') : '';
$page = is_array($data) ? (string)($data['page'] ?? '/') : '/';
$allowed = ['page_view', 'whatsapp_click', 'contact_click', 'order_click'];
if (!in_array($type, $allowed, true) || !preg_match('/^[a-f0-9]{32,64}$/', $visitor)) {
    http_response_code(400);
    echo json_encode(['ok' => false]);
    exit;
}
$page = '/' . ltrim((string)(parse_url($page, PHP_URL_PATH) ?: '/'), '/');
$page = substr(preg_replace('/[^a-zA-Z0-9_\-\/.]/', '', $page) ?: '/', 0, 255);
if (str_contains($page, '..')) $page = '/';
try {
    $hash = hash('sha256', $visitor);
    $stmt = db()->prepare('INSERT INTO analytics_events (visitor_hash,event_type,page_path) VALUES (?,?,?)');
    $stmt->execute([$hash, $type, $page]);
    echo json_encode(['ok' => true]);
} catch (Throwable $e) {
    http_response_code(503);
    echo json_encode(['ok' => false]);
}
