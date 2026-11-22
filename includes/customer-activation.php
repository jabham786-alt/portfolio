<?php
declare(strict_types=1);

/**
 * Create a fresh activation token. Only the SHA-256 digest is persisted.
 * The caller must ensure the customer has already been approved by an admin.
 */
function customer_create_activation_token(PDO $pdo, int $customerId): string {
    $raw = bin2hex(random_bytes(32));
    $hash = hash('sha256', $raw);
    $invalidate = $pdo->prepare('UPDATE customer_activation_tokens SET consumed_at=NOW() WHERE customer_id=? AND consumed_at IS NULL');
    $invalidate->execute([$customerId]);
    $insert = $pdo->prepare('INSERT INTO customer_activation_tokens (customer_id,token_hash,expires_at,created_at) VALUES (?,?,DATE_ADD(NOW(), INTERVAL 48 HOUR),NOW())');
    $insert->execute([$customerId,$hash]);
    return $raw;
}

function customer_activation_base_url(): string {
    $configured = defined('SITE_URL') ? trim((string)SITE_URL) : '';
    if ($configured === '') $configured = 'https://hammadgraphic.site.je';
    $parts = parse_url($configured);
    if (!is_array($parts) || empty($parts['host']) || !in_array(strtolower((string)($parts['scheme'] ?? '')), ['https','http'], true)) {
        throw new RuntimeException('Set a valid SITE_URL in includes/config.php before generating activation links.');
    }
    if (strtolower((string)$parts['scheme']) !== 'https' && !in_array(strtolower((string)$parts['host']), ['localhost','127.0.0.1'], true)) {
        throw new RuntimeException('Customer activation links must use HTTPS.');
    }
    return rtrim($configured, '/');
}

function customer_mobile_digits(string $mobile): string {
    $digits = preg_replace('/\D+/', '', $mobile) ?? '';
    if (str_starts_with($digits, '00')) $digits = substr($digits, 2);
    if (strlen($digits) < 10 || strlen($digits) > 15) return '';
    return $digits;
}
