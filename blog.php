<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/customer-auth.php';
$customer = customer_current();
if (!$customer) redirect('welcome.php');
$posts = [];
$blogReady = true;
try {
    $posts = db()->query("SELECT id,title,slug,excerpt,cover_image,published_at FROM blog_posts WHERE status='published' AND published_at<=NOW() ORDER BY published_at DESC,id DESC LIMIT 30")->fetchAll();
} catch (Throwable $e) {
    $blogReady = false;
    error_log('Blog listing unavailable: '.$e->getMessage());
}
?><!doctype html><html lang="en" data-theme="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Design Journal — Hammad Graphic</title><link rel="stylesheet" href="assets/css/style.css"><link rel="stylesheet" href="assets/css/customer-app.css"></head><body class="app-body"><main class="app-content" style="max-width:1100px"><div style="display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:35px"><a class="brand" href="home.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC</span></span></a><div style="display:flex;gap:10px"><button class="app-icon-btn" data-theme-toggle type="button" aria-label="Switch colour theme">☼</button><a class="button button-outline" href="home.php">Back to studio</a></div></div><div class="app-kicker">IDEAS · PROCESS · INSPIRATION</div><h1 style="font:600 clamp(38px,6vw,66px)/1 var(--display);letter-spacing:-3px;margin:14px 0 12px">The design <em style="color:var(--accent);font-style:normal">journal.</em></h1><p style="color:var(--muted);max-width:560px">Notes, ideas and practical insights from the Hammad Graphic studio.</p>
<?php if(!$blogReady): ?><div class="app-empty" style="margin-top:30px">The Design Journal is being prepared. The administrator must run the additive blog database migration before publishing articles.</div><?php elseif(!$posts): ?><div class="app-empty" style="margin-top:30px">No articles have been published yet. Please check back soon.</div><?php else: ?><div class="app-portfolio-grid" style="margin-top:35px"><?php foreach($posts as $post): ?><article class="app-portfolio-card"><?php if($post['cover_image']): ?><div class="app-portfolio-media"><img loading="lazy" src="assets/uploads/blog/<?=e(rawurlencode((string)$post['cover_image']))?>" alt="<?=e($post['title'])?>"></div><?php endif; ?><div class="app-portfolio-info"><small><?=e(date('d M Y',strtotime((string)$post['published_at'])))?></small><h3><?=e($post['title'])?></h3><p><?=e($post['excerpt']??'')?></p><a href="blog-post.php?slug=<?=rawurlencode((string)$post['slug'])?>" style="color:var(--accent);font-size:11px;font-weight:700">Read article →</a></div></article><?php endforeach; ?></div><?php endif; ?></main><script src="assets/js/customer-app.js" defer></script></body></html>