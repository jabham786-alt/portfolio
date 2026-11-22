<?php
// Shared public-page analytics snippet. Requires includes/bootstrap.php first.
$cfAnalyticsToken = '';
try {
    $cfAnalyticsToken = (string)(db()->query("SELECT setting_value FROM site_settings WHERE setting_key='cloudflare_analytics_token'")->fetchColumn() ?: '');
} catch (Throwable $ignored) {}
?>
<script src="assets/js/analytics.js?v=1.0.0" defer></script>
<?php if ($cfAnalyticsToken !== ''): ?>
<script type="module" src="https://static.cloudflareinsights.com/beacon.min.js" data-cf-beacon="<?=e(json_encode(['token' => $cfAnalyticsToken], JSON_UNESCAPED_SLASHES))?>"></script>
<?php endif; ?>
