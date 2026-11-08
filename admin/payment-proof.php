<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';
if (empty($_SESSION['admin_id'])) { http_response_code(403); exit('Forbidden'); }
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { http_response_code(404); exit('Receipt not found.'); }
$q = db()->prepare('SELECT payment_proof FROM orders WHERE id = ?');
$q->execute([$id]);
$file = $q->fetchColumn();
if (!is_string($file) || !preg_match('/^payment-[a-f0-9]{36}\.(jpg|png|webp|pdf)$/', $file)) { http_response_code(404); exit('Receipt not found.'); }
$path = UPLOAD_DIRECTORY . '/' . $file;
if (!is_file($path)) { http_response_code(404); exit('Receipt file is missing.'); }
$mime = (new finfo(FILEINFO_MIME_TYPE))->file($path);
if (!in_array($mime, ['image/jpeg','image/png','image/webp','application/pdf'], true)) { http_response_code(415); exit('Unsupported receipt type.'); }
header('Content-Type: ' . $mime);
header('Content-Length: ' . (string) filesize($path));
header('Content-Disposition: inline; filename="payment-receipt-' . $id . '"');
header('X-Content-Type-Options: nosniff');
readfile($path);
