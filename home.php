<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/customer-auth.php';

$customer = customer_current();
if (!$customer) redirect('welcome.php');

$services = [
 ['Brand identity','Logo design, brand colours, typography and a consistent visual identity.','✳'],
 ['Social media design','Posts, stories, covers and campaign graphics for your social channels.','▧'],
 ['Print & business stationery','Business cards, flyers, posters, brochures and print-ready layouts.','▤'],
 ['YouTube thumbnails','Bold, clear thumbnail designs made to communicate at a glance.','▶'],
 ['Custom creative design','Tailored graphics and visual assets for your next creative idea.','✦'],
];
$publishedWork = [];
try {
    $publishedWork = db()->query("SELECT id,title,category,description,media_kind,media_path,external_url,project_date FROM portfolio_items WHERE status='published' ORDER BY published_at DESC,id DESC LIMIT 12")->fetchAll();
} catch (Throwable $ignored) {}
$initials = '';
foreach (preg_split('/\s+/', trim((string)$customer['full_name'])) ?: [] as $part) {
    if ($part !== '') $initials .= strtoupper(substr($part, 0, 1));
    if (strlen($initials) >= 2) break;
}
?><!doctype html>
<html lang="en" data-theme="dark">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="theme-color" content="#101018">
<meta name="description" content="Your Hammad Graphic creative studio — explore design services, portfolio and place your next order.">
<title>Studio Home — Hammad Graphic</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css?v=1.0.0"><link rel="stylesheet" href="assets/css/customer-app.css?v=1.0.0">
</head>
<body class="app-body">
<div class="app-shell">
<aside class="app-sidebar">
  <div class="app-brand"><a class="brand" href="home.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC STUDIO</span></span></a></div>
  <div class="app-nav-label">WORKSPACE</div>
  <nav class="app-nav" aria-label="Main navigation">
    <a class="active" href="home.php"><span class="nav-symbol">⌂</span> Overview</a>
    <a href="#app-services"><span class="nav-symbol">✳</span> Explore services</a>
    <a href="#app-portfolio"><span class="nav-symbol">▦</span> Portfolio</a>
    <a href="customer/index.php"><span class="nav-symbol">▤</span> My orders</a>
    <a href="customer/index.php"><span class="nav-symbol">◉</span> My account</a>
    <a href="blog.php"><span class="nav-symbol">▧</span> Design journal</a>
  </nav>
  <div class="app-sidebar-bottom">
    <div class="app-nav-label">SUPPORT</div>
    <a class="button button-primary" href="<?=e(whatsapp_url('Hello Hammad, I need help with a design project.'))?>" target="_blank" rel="noopener">WhatsApp support ↗</a>
    <div class="app-profile-mini"><span class="app-avatar"><?=e($initials ?: 'HG')?></span><span style="min-width:0"><strong><?=e($customer['full_name'])?></strong><small>Customer account</small></span></div>
    <form class="app-logout-form" method="post" action="logout.php"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><button type="submit"><span class="nav-symbol">↗</span> Sign out</button></form>
  </div>
</aside>
<main class="app-main">
  <header class="app-topbar">
    <div class="app-topbar-left"><button class="app-icon-btn app-mobile-menu" type="button" data-app-menu aria-label="Open menu">☰</button><span>HAMMAD GRAPHIC / CUSTOMER STUDIO</span></div>
    <div class="app-topbar-right"><span class="app-theme-label">Appearance</span><button class="app-icon-btn" type="button" data-theme-toggle aria-label="Switch colour theme">☼</button><a class="button button-outline" href="customer/index.php">My account ↗</a></div>
  </header>
  <div class="app-content">
    <section class="app-welcome"><div><div class="app-kicker">YOUR CREATIVE WORKSPACE</div><h1>Welcome back, <?=e($customer['full_name'])?>.</h1><p>Find the right design service and turn your idea into something memorable.</p></div><a class="button button-primary" href="order.php">Start a new order <span>↗</span></a></section>
    <section class="app-hero" aria-label="Featured studio highlights">
      <article class="app-slide app-slide-one active"><div class="app-slide-content"><div class="app-kicker">01 / BRAND & IDENTITY</div><h2>Make your brand<br>impossible to ignore.</h2><p>Build a visual identity that feels clear, confident and unmistakably yours.</p><a class="button button-primary" href="order.php?service=Brand%20identity">Explore brand design ↗</a></div><div class="app-slide-deco purple"><span>HAMMAD GRAPHIC / 001</span><b>MAKE<br>IT<br>MATTER.</b><span>IDENTITY · VISUALS</span></div></article>
      <article class="app-slide app-slide-two"><div class="app-slide-content"><div class="app-kicker">02 / SOCIAL MEDIA DESIGN</div><h2>Show up with<br>something to say.</h2><p>Creative social media visuals that help your message stand out in a busy feed.</p><a class="button button-primary" href="order.php?service=Social%20media%20design">Create social graphics ↗</a></div><div class="app-slide-deco"><span>CONTENT SERIES / 002</span><b>GOOD<br>IDEAS<br>TRAVEL.</b><span>POSTS · STORIES · COVERS</span></div></article>
      <article class="app-slide app-slide-three"><div class="app-slide-content"><div class="app-kicker">03 / CUSTOM CREATIVE</div><h2>Your next idea<br>starts right here.</h2><p>Tell us what you are imagining. We will shape the brief and plan the right design.</p><a class="button button-primary" href="order.php?service=Custom%20creative%20design">Discuss your idea ↗</a></div><div class="app-slide-deco orange"><span>CREATIVE STUDIO / 003</span><b>IDEAS<br>INTO<br>IMPACT.</b><span>THOUGHTFUL BY DESIGN</span></div></article>
      <div class="app-slider-controls" aria-label="Slideshow controls"><button class="app-slider-dot active" type="button" aria-label="Show slide 1"></button><button class="app-slider-dot" type="button" aria-label="Show slide 2"></button><button class="app-slider-dot" type="button" aria-label="Show slide 3"></button></div>
    </section>
    <section class="app-section" id="app-services">
      <div class="app-section-head"><div><div class="app-kicker">WHAT WE CAN CREATE</div><h2>Explore our services</h2></div><p>Choose a service to start your order.</p></div>
      <div class="app-service-grid"><?php foreach($services as $service): ?><article class="app-service-card"><div class="service-icon"><?=e($service[2])?></div><h3><?=e($service[0])?></h3><p><?=e($service[1])?></p><a href="order.php?service=<?=rawurlencode($service[0])?>">Choose this service <span>→</span></a></article><?php endforeach; ?></div>
    </section>
    <section class="app-section" id="app-portfolio">
      <div class="app-section-head"><div><div class="app-kicker">SELECTED STUDIO WORK</div><h2>Portfolio highlights</h2></div><p>Published projects from the studio.</p></div>
      <?php if($publishedWork): ?><div class="app-portfolio-grid"><?php foreach($publishedWork as $item): ?><article class="app-portfolio-card"><div class="app-portfolio-media"><?php if($item['media_kind']==='image' && $item['media_path']): ?><img loading="lazy" src="assets/uploads/portfolio/<?=e(rawurlencode((string)$item['media_path']))?>" alt="<?=e($item['title'])?>"><?php elseif($item['media_kind']==='video' && $item['media_path']): ?><video controls preload="metadata" src="assets/uploads/portfolio/<?=e(rawurlencode((string)$item['media_path']))?>"></video><?php elseif($item['media_kind']==='audio' && $item['media_path']): ?><audio controls preload="none" src="assets/uploads/portfolio/<?=e(rawurlencode((string)$item['media_path']))?>"></audio><?php elseif($item['external_url']): ?><a class="button button-outline" href="<?=e($item['external_url'])?>" target="_blank" rel="noopener">Open project ↗</a><?php else: ?><span class="app-kicker">HAMMAD GRAPHIC</span><?php endif; ?></div><div class="app-portfolio-info"><small><?=e($item['category'])?><?php if($item['project_date']): ?> · <?=e(date('M Y',strtotime((string)$item['project_date'])))?><?php endif; ?></small><h3><?=e($item['title'])?></h3><?php if($item['description']): ?><p><?=e($item['description'])?></p><?php endif; ?></div></article><?php endforeach; ?></div><?php else: ?><div class="app-empty">Your published portfolio projects will appear here. New work can be added and published from the Admin Panel.</div><?php endif; ?>
    </section>
    <section class="app-section"><div class="app-section-head"><div><div class="app-kicker">READY WHEN YOU ARE</div><h2>Have a project in mind?</h2></div></div><div class="app-empty" style="display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap"><span>Choose a service, share your brief and track the order from your account.</span><a class="button button-primary" href="order.php">Create an order ↗</a></div></section>
  </div>
</main>
</div>
<script src="assets/js/customer-app.js?v=1.0.0" defer></script>
<?php require __DIR__ . '/includes/analytics-snippet.php'; ?>
</body></html>