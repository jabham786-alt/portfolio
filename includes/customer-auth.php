<?php
declare(strict_types=1);

/** Customer authentication is separate from the existing admin session. */
function customer_current(): ?array {
    $id = filter_var($_SESSION['customer_id'] ?? null, FILTER_VALIDATE_INT);
    if (!$id) return null;
    $stmt = db()->prepare("SELECT id, full_name, email, mobile, username, account_status FROM customers WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $customer = $stmt->fetch();
    if (!$customer || $customer['account_status'] !== 'active') {
        unset($_SESSION['customer_id']);
        return null;
    }
    return $customer;
}

function customer_require_login(): array {
    $customer = customer_current();
    if (!$customer) {
        $next = basename((string)($_SERVER['PHP_SELF'] ?? 'index.php'));
        if (!in_array($next, ['index.php','orders.php','profile.php'], true)) $next = 'index.php';
        redirect('../login.php?next=' . rawurlencode('customer/' . $next));
    }
    return $customer;
}

function customer_login(int $customerId): void {
    session_regenerate_id(true);
    $_SESSION['customer_id'] = $customerId;
    $stmt = db()->prepare('UPDATE customers SET last_login_at = NOW() WHERE id = ?');
    $stmt->execute([$customerId]);
}

function customer_logout(): void {
    unset($_SESSION['customer_id']);
    session_regenerate_id(true);
}
