<?php
declare(strict_types=1);

$waConfigFile = __DIR__ . '/whatsapp-config.php';
if (is_file($waConfigFile)) require_once $waConfigFile;

/** Convert Pakistan local mobile formats to WhatsApp international digits. */
function wa_normalize_mobile(string $mobile): string {
    $digits = preg_replace('/\D+/', '', $mobile) ?? '';
    if (str_starts_with($digits, '00')) $digits = substr($digits, 2);
    if (str_starts_with($digits, '0') && strlen($digits) === 11) $digits = '92' . substr($digits, 1);
    if (str_starts_with($digits, '3') && strlen($digits) === 10) $digits = '92' . $digits;
    if (strlen($digits) < 10 || strlen($digits) > 15) {
        throw new InvalidArgumentException('Enter a valid WhatsApp number with country code, e.g. +923001234567.');
    }
    return $digits;
}

/** Send an approved WhatsApp AUTHENTICATION template via Meta Cloud API. */
function wa_send_registration_otp(string $to, string $otp): void {
    foreach (['WA_GRAPH_VERSION','WA_PHONE_NUMBER_ID','WA_ACCESS_TOKEN','WA_OTP_TEMPLATE','WA_OTP_LANGUAGE'] as $constant) {
        if (!defined($constant) || constant($constant) === '' || str_contains((string)constant($constant), 'YOUR_')) {
            throw new RuntimeException('WhatsApp API is not configured.');
        }
    }
    if (!function_exists('curl_init')) throw new RuntimeException('PHP cURL extension is not available on this hosting plan.');

    $payload = [
        'messaging_product' => 'whatsapp',
        'recipient_type' => 'individual',
        'to' => $to,
        'type' => 'template',
        'template' => [
            'name' => WA_OTP_TEMPLATE,
            'language' => ['code' => WA_OTP_LANGUAGE],
            'components' => [
                ['type' => 'body', 'parameters' => [['type' => 'text', 'text' => $otp]]],
                ['type' => 'button', 'sub_type' => 'url', 'index' => '0', 'parameters' => [['type' => 'text', 'text' => $otp]]],
            ],
        ],
    ];
    $url = 'https://graph.facebook.com/' . rawurlencode(WA_GRAPH_VERSION) . '/' . rawurlencode(WA_PHONE_NUMBER_ID) . '/messages';
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . WA_ACCESS_TOKEN, 'Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_THROW_ON_ERROR),
    ]);
    $response = curl_exec($ch);
    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false || $status < 200 || $status >= 300) {
        // Do not log access token, OTP, recipient number, or raw API request.
        error_log('WhatsApp OTP delivery failed. HTTP=' . $status . ' cURL=' . $curlError);
        throw new RuntimeException('WhatsApp could not send the code. Check Meta configuration and approved template.');
    }
    $decoded = json_decode((string)$response, true);
    if (!is_array($decoded) || empty($decoded['messages'][0]['id'])) {
        error_log('WhatsApp OTP API returned an unexpected response. HTTP=' . $status);
        throw new RuntimeException('WhatsApp did not confirm message acceptance.');
    }
}

/** Generate, hash, persist and send a fresh 6-digit registration OTP. */
function customer_issue_whatsapp_otp(int $customerId, string $mobile): void {
    $pdo = db();
    $to = wa_normalize_mobile($mobile);
    $check = $pdo->prepare('SELECT sent_at FROM customer_registration_otps WHERE customer_id=? LIMIT 1');
    $check->execute([$customerId]);
    $previous = $check->fetch();
    if ($previous && strtotime((string)$previous['sent_at']) > time() - 60) {
        throw new RuntimeException('Please wait 60 seconds before requesting another code.');
    }

    $otp = (string)random_int(100000, 999999);
    $hash = password_hash($otp, PASSWORD_DEFAULT);
    $save = $pdo->prepare('INSERT INTO customer_registration_otps (customer_id, otp_hash, expires_at, attempts, sent_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 5 MINUTE), 0, NOW()) ON DUPLICATE KEY UPDATE otp_hash=VALUES(otp_hash), expires_at=VALUES(expires_at), attempts=0, sent_at=NOW()');
    $save->execute([$customerId, $hash]);

    try {
        wa_send_registration_otp($to, $otp);
    } catch (Throwable $e) {
        $delete = $pdo->prepare('DELETE FROM customer_registration_otps WHERE customer_id=?');
        $delete->execute([$customerId]);
        throw $e;
    }
}
