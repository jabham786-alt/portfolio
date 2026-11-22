<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';

$slug = trim((string)($_GET['slug'] ?? ''));
$post = null;
try {
    if ($slug !== '' && strlen($slug) <= 190) {
        $stmt = db()->prepare("SELECT title,excerpt,body,cover_image,published_at FROM blog_posts WHERE slug=? AND status='published' AND published_at<=NOW() LIMIT 1");
        $stmt->execute([$slug]);
        $post = $stmt->fetch() ?: null;
    }
} catch (Throwable $e) {
    error_log('Public design journal article lookup failed: ' . $e->getMessage());
}
if (!$post) http_response_code(404);
?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($post['title'] ?? 'Article not found')?> — Hammad Graphic</title>
<link rel="stylesheet" href="assets/css/style.css?v=1.0.0">
<link rel="stylesheet" href="assets/css/customer-app.css?v=1.0.0">
</head>
<body class="app-body">
<header class="site-header">
  <a class="brand" href="index.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC</span></span></a>
  <nav class="main-nav"><a href="blog.php">Design Journal</a><a href="index.php">Home</a></nav>
</header>
<main class="app-content" style="max-width:900px">
  <?php if (!$post): ?>
    <div class="app-empty">This article is unavailable or has not been published. <a href="blog.php">Return to the Design Journal</a>.</div>
  <?php else: ?>
    <div class="app-kicker">DESIGN JOURNAL · <?=e(date('d M Y', strtotime((string)$post['published_at'])))?></div>
    <h1 style="font:600 clamp(38px,6vw,66px)/1.05 var(--display);letter-spacing:-3px;margin:18px 0"><?=e($post['title'])?></h1>
    <?php if (!empty($post['excerpt'])): ?><p style="font-size:17px;color:var(--muted);line-height:1.8"><?=e($post['excerpt'])?></p><?php endif; ?>
    <?php if (!empty($post['cover_image'])): ?><img src="assets/uploads/blog/<?=e(rawurlencode((string)$post['cover_image']))?>" alt="<?=e($post['title'])?>" style="width:100%;max-height:480px;object-fit:cover;border-radius:14px;margin:25px 0"><?php endif; ?>
    <article style="white-space:pre-wrap;line-height:2;font-size:15px;max-width:760px"><?=e($post['body'])?></article>
  <?php endif; ?>
</main>
<script src="assets/js/customer-app.js?v=1.0.0" defer></script>
</body>
</html>
