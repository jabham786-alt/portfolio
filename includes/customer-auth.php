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

/**
 * Accept only known, same-site destinations. Query parameters are allow-listed
 * per destination so a login link cannot become an open redirect.
 */
function customer_safe_next(string $candidate, string $default = 'customer/index.php'): string {
    $allowedPaths = ['customer/index.php', 'order.php', 'web-designing/index.php'];
    if (!in_array($default, $allowedPaths, true)) $default = 'customer/index.php';

    $candidate = trim($candidate);
    if ($candidate === '' || preg_match('/[\x00-\x20\x7f\\\\]/', $candidate)) return $default;
    if (str_starts_with($candidate, '/') || str_contains($candidate, '#')) return $default;

    $parts = parse_url($candidate);
    if ($parts === false || isset($parts['scheme']) || isset($parts['host']) || isset($parts['user']) || isset($parts['pass']) || isset($parts['port']) || isset($parts['fragment'])) {
        return $default;
    }

    $path = (string)($parts['path'] ?? '');
    if ($path === '' || rawurldecode($path) !== $path || str_contains($path, '..') || !in_array($path, $allowedPaths, true)) {
        return $default;
    }

    $query = [];
    if (isset($parts['query'])) parse_str((string)$parts['query'], $query);

    if ($path === 'order.php') {
        $service = $query['service'] ?? '';
        if (!is_string($service)) return 'order.php';
        $services = ['Brand identity', 'Social media design', 'Print & business stationery', 'YouTube thumbnails', 'Custom creative design'];
        return in_array($service, $services, true) ? 'order.php?' . http_build_query(['service' => $service]) : 'order.php';
    }

    if ($path === 'web-designing/index.php') {
        $package = $query['package'] ?? '';
        if (!is_string($package)) return 'web-designing/index.php';
        return in_array($package, ['design', 'development', 'complete'], true)
            ? 'web-designing/index.php?' . http_build_query(['package' => $package])
            : 'web-designing/index.php';
    }

    return 'customer/index.php';
}

function customer_require_login(): array {
    $customer = customer_current();
    if (!$customer) {
        $script = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? ''));
        $route = 'customer/index.php';
        $marker = '/customer/';
        $position = strrpos($script, $marker);
        if ($position !== false) {
            $candidate = 'customer/' . basename(substr($script, $position + strlen($marker)));
            $route = customer_safe_next($candidate);
        }
        redirect('../login.php?next=' . rawurlencode($route));
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
