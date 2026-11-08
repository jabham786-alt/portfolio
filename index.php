<?php
require_once __DIR__ . '/includes/bootstrap.php';
$site = [];
try { $site = db()->query('SELECT setting_key,setting_value FROM site_settings')->fetchAll(PDO::FETCH_KEY_PAIR); } catch (Throwable $ignored) {}
$heroHeading = (string)($site['hero_heading'] ?? "Good design\\nmakes you look twice.");
$heroText = (string)($site['hero_text'] ?? "Thoughtful visuals for brands, businesses and people with something to say. Let’s turn your next idea into a design that feels unmistakably yours.");
$aboutText = (string)($site['about_text'] ?? "I’m Hammad Ali, the graphic designer and founder behind Hammad Graphic. I create visual work for businesses, creators and growing brands — with a focus on clarity, personality and the details that make a design feel right.");
$contactEmail = (string)($site['contact_email'] ?? 'jabham786@gmail.com');
$portrait = !empty($site['portrait_path']) ? 'assets/uploads/site/'.rawurlencode((string)$site['portrait_path']) : 'assets/images/hammad-ali.jpg';
$publishedWork = [];
try { $publishedWork = db()->query("SELECT * FROM portfolio_items WHERE status='published' ORDER BY published_at DESC, id DESC LIMIT 30")->fetchAll(); } catch (Throwable $ignored) { /* migration not installed yet */ }
$services = [
 ['01','Brand identity','Logo design, colour systems, typography and a consistent visual direction.'],
 ['02','Social media design','Scroll-stopping posts, stories, covers and campaign graphics.'],
 ['03','Print & business stationery','Business cards, flyers, posters, brochures and print-ready layouts.'],
 ['04','YouTube thumbnails','Clear, bold thumbnail concepts designed to earn attention.'],
 ['05','Custom creative design','One-off graphics and tailored visual assets for your next idea.'],
];
?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#101018">
<meta name="description" content="Hammad Graphic — creative graphic design for brands, social media and businesses. Request a custom design quote.">
<title>Hammad Graphic — Design with a point of view</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css?v=1.0.0">
<style>.owner-photo-frame{position:absolute;z-index:5;left:50%;top:50%;transform:translate(-50%,-50%);width:190px;height:235px;border:7px solid var(--bg);background:var(--surface2);overflow:hidden;display:grid;place-items:center;text-align:center;font-size:10px}.owner-photo-frame img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}.about-visual{overflow:hidden}.about-owner-photo{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.25}.about-monogram{z-index:2}@media(max-width:700px){.owner-photo-frame{width:145px;height:185px}}.published-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}.published-card{background:var(--surface);border:1px solid var(--line);min-width:0}.published-media{min-height:190px;background:var(--surface2);display:grid;place-items:center;overflow:hidden}.published-media img,.published-media video{width:100%;height:240px;object-fit:cover}.published-media audio{width:100%;max-width:100%}.audio-demo{width:100%;padding:28px;display:grid;gap:20px;color:var(--accent)}.audio-demo span{font:700 10px var(--display);letter-spacing:2px}.published-caption{padding:18px}.published-caption>span,.published-caption small{font-size:9px;color:var(--muted);letter-spacing:1px}.published-caption h3{font:600 19px var(--display);margin:9px 0}.published-caption p{font-size:11px;color:var(--muted);line-height:1.8}.meeting-band{background:var(--surface);border-block:1px solid var(--line)}.meeting-inner{padding-block:75px;display:grid;grid-template-columns:1fr .8fr;gap:70px;align-items:center}.meeting-inner h2{font:600 clamp(42px,5vw,64px)/1.05 var(--display);letter-spacing:-3px;margin:20px 0}.meeting-inner h2 em{font-style:normal;color:var(--accent)}.meeting-inner p{max-width:520px;color:var(--muted);font-size:13px;line-height:1.9;margin-bottom:25px}.meeting-card{min-height:230px;border:1px solid var(--line);background:var(--bg);padding:30px;display:flex;flex-direction:column;justify-content:center;gap:14px}.meeting-icon{font-size:34px;color:var(--accent)}.meeting-card strong{font:700 12px var(--display);letter-spacing:2px}.meeting-card span,.meeting-card small{font-size:11px;color:var(--muted);line-height:1.8}@media(max-width:700px){.published-grid{grid-template-columns:1fr}.meeting-inner{grid-template-columns:1fr;gap:30px}.published-media img,.published-media video{height:220px}}</style></head>
<body>
<header class="site-header">
<a class="brand" href="index.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC</span></span></a>
<button class="menu-toggle" id="menuToggle" aria-label="Open navigation" aria-expanded="false"><span></span><span></span></button>
<nav class="main-nav" id="mainNav"><a href="#services">Services</a><a href="#work">Portfolio</a><a href="#meetings">Zoom meeting</a><a href="#about">About</a><a href="#contact">Contact</a><a href="login.php">Customer login</a><a href="register.php">Register</a><a class="nav-order" href="order.php">Start a project <span>↗</span></a></nav>
<button class="theme-toggle" id="themeToggle" type="button" aria-label="Toggle colour theme"><span class="theme-icon">☼</span></button>
</header>
<main>
<section class="hero section-wrap">
<div class="hero-copy">
<div class="eyebrow"><span class="eyebrow-line"></span> INDEPENDENT GRAPHIC DESIGN STUDIO</div>
<h1><?= nl2br(e($heroHeading)) ?></h1>
<p class="hero-lead"><?= e($heroText) ?></p>
<div class="hero-actions"><a class="button button-primary" href="order.php">Start a project <span>↗</span></a><a class="text-link" href="#work">Explore selected work <span>↓</span></a></div>
<div class="hero-meta"><div><strong>HAMMAD ALI</strong><span>Graphic Designer</span></div><div class="meta-divider"></div><div><strong>BASED IN PAKISTAN</strong><span>Working with ideas everywhere</span></div></div>
</div>
<div class="hero-art" aria-label="Hammad Ali graphic designer"><div class="owner-photo-frame"><img src="<?= e($portrait) ?>" alt="Hammad Ali, graphic designer"><span>ADD YOUR PHOTO<br>assets/images/hammad-ali.jpg</span></div>
<div class="art-orbit orbit-one"></div><div class="art-orbit orbit-two"></div>
<div class="art-panel art-panel-back"><span>MAKE IT</span><b>MATTER.</b><i>VISUAL / 001</i></div>
<div class="art-panel art-panel-front"><div class="art-topline"><span>HG®</span><span>CREATIVE STUDIO</span></div><div class="art-sun"></div><div class="art-type">IDEAS<br><em>INTO</em><br>IMPACT.</div><div class="art-bottomline"><span>IDENTITY · DIGITAL · PRINT</span><span>2026</span></div></div>
<div class="art-sticker">DESIGN<br>WITH<br>INTENT <span>✳</span></div><div class="art-caption">A LITTLE DIFFERENT<br>BY DESIGN.</div>
</div>
</section>
<section class="ticker"><div class="ticker-track"><span>BRAND IDENTITY ✳ SOCIAL MEDIA ✳ PRINT DESIGN ✳ THUMBNAILS ✳ CREATIVE DIRECTION ✳ </span><span aria-hidden="true">BRAND IDENTITY ✳ SOCIAL MEDIA ✳ PRINT DESIGN ✳ THUMBNAILS ✳ CREATIVE DIRECTION ✳ </span></div></section>
<section class="section-wrap section-block" id="services">
<div class="section-heading"><div><div class="eyebrow">WHAT I CAN CREATE</div><h2>Design that works<br><em>as hard as you do.</em></h2></div><p>From the first sketch to the final export, every detail has a job. Choose a service or tell me what you have in mind.</p></div>
<div class="services-grid"><?php foreach ($services as $service): ?><article class="service-card"><div class="service-number"><?= e($service[0]) ?></div><div class="service-arrow">↗</div><h3><?= e($service[1]) ?></h3><p><?= e($service[2]) ?></p><a href="order.php?service=<?= rawurlencode($service[1]) ?>" class="card-link">Request this service <span>→</span></a></article><?php endforeach; ?></div>
</section>
<section class="work-section" id="work"><div class="section-wrap section-block">
<div class="section-heading"><div><div class="eyebrow">A FEEL FOR THE WORK</div><h2>Selected visual<br><em>directions.</em></h2></div><p>These are original concept compositions to show the studio’s visual direction. Client projects can be added here as your portfolio grows.</p></div>
<div class="work-grid">
<article class="work-card work-wide"><div class="work-art work-art-one"><div class="poster-label">01 / BRAND STUDY</div><div class="poster-giant">FORM<br><i>FOLLOWS</i><br>FEELING.</div><div class="poster-circle"></div><div class="poster-foot">A STUDY IN SHAPE & TYPE</div></div><div class="work-caption"><div><span>CONCEPT 01</span><h3>Form & feeling</h3></div><span class="work-category">ART DIRECTION</span></div></article>
<article class="work-card"><div class="work-art work-art-two"><div class="social-frame"><span>THE DAILY<br>GOOD.</span><b>MAKE<br>ROOM FOR<br>GOOD IDEAS.</b><i>YOUR NEXT CHAPTER STARTS HERE</i></div><div class="social-side">SOCIAL SERIES / 02</div></div><div class="work-caption"><div><span>CONCEPT 02</span><h3>The daily good</h3></div><span class="work-category">SOCIAL DESIGN</span></div></article>
<article class="work-card"><div class="work-art work-art-three"><div class="thumb-stamp">CLICK<br>WITH<br>INTENT</div><div class="thumb-play">▶</div><div class="thumb-title">MAKE<br>IT <i>STAND</i><br>OUT.</div><div class="thumb-small">THUMBNAIL STUDY / 03</div></div><div class="work-caption"><div><span>CONCEPT 03</span><h3>Make it stand out</h3></div><span class="work-category">THUMBNAIL</span></div></article>
</div>
</div></section>
<?php require __DIR__ . '/website-services.php'; ?>
<?php if ($publishedWork): ?>
<section class="section-wrap section-block" id="client-work"><div class="section-heading"><div><div class="eyebrow">RECENTLY PUBLISHED</div><h2>Work made for<br><em>real projects.</em></h2></div><p>Selected work shared with permission. New projects appear here only after review and publishing.</p></div>
<div class="published-grid"><?php foreach($publishedWork as $item): ?><article class="published-card"><div class="published-media"><?php if($item['media_kind']==='image' && $item['media_path']): ?><img src="assets/uploads/portfolio/<?=e($item['media_path'])?>" alt="<?=e($item['title'])?>" loading="lazy"><?php elseif($item['media_kind']==='video' && $item['media_path']): ?><video controls preload="metadata" src="assets/uploads/portfolio/<?=e($item['media_path'])?>"></video><?php elseif($item['media_kind']==='audio' && $item['media_path']): ?><div class="audio-demo"><span>♪ AUDIO DEMO</span><audio controls preload="none" src="assets/uploads/portfolio/<?=e($item['media_path'])?>"></audio></div><?php elseif($item['external_url']): ?><a class="button button-outline" href="<?=e($item['external_url'])?>" target="_blank" rel="noopener">Open demo ↗</a><?php endif; ?></div><div class="published-caption"><span><?=e($item['category'])?><?php if($item['project_date']): ?> · <?=e(date('M Y',strtotime($item['project_date'])))?><?php endif; ?></span><h3><?=e($item['title'])?></h3><?php if($item['description']): ?><p><?=nl2br(e($item['description']))?></p><?php endif; ?><?php if($item['customer_label']): ?><small><?=e($item['customer_label'])?></small><?php endif; ?></div></article><?php endforeach; ?></div></section>
<?php endif; ?>
<section class="meeting-band" id="meetings"><div class="section-wrap meeting-inner"><div><div class="eyebrow">LET’S DISCUSS YOUR BRIEF</div><h2>Need a project<br><em>consultation?</em></h2><p>Want to explain your idea, review design references or discuss a project face to face? Send a Zoom meeting request with your preferred time.</p><a class="button button-primary" href="zoom-meeting.php">Request a Zoom meeting <span>↗</span></a></div><div class="meeting-card"><div class="meeting-icon">↗</div><strong>ZOOM PROJECT CALL</strong><span>Request a time · Get confirmation · Receive your private meeting link</span><small>Meetings are confirmed manually after review.</small></div></div></section>
<section class="section-wrap about-section section-block" id="about">
<div class="about-visual"><img class="about-owner-photo" src="<?= e($portrait) ?>" alt="Hammad Ali — Graphic Designer"><div class="about-monogram">H<span>G</span></div><div class="about-note">GOOD IDEAS<br>DESERVE<br>GOOD DESIGN.</div><div class="about-orbit"></div></div>
<div class="about-copy"><div class="eyebrow">A LITTLE ABOUT THE DESIGNER</div><h2>Hello, I’m<br><em>Hammad Ali.</em></h2><p class="about-intro"><?= e($aboutText) ?></p><p>Whether you need a new identity, a social media set or a single standout graphic, tell me what you’re building and we can shape the right direction together.</p><div class="skill-tags"><span>Adobe Photoshop</span><span>Adobe Illustrator</span><span>Social media design</span><span>Brand visuals</span><span>Print design</span></div><a href="order.php" class="text-link">Tell me about your project <span>↗</span></a></div>
</section>
<section class="contact-band" id="contact"><div class="section-wrap contact-inner"><div><div class="eyebrow">HAVE SOMETHING IN MIND?</div><h2>Let’s make it<br><em>happen.</em></h2></div><div class="contact-side"><p>Share a little about your idea. I’ll review the brief and follow up to discuss the next step.</p><a class="button button-light" href="order.php">Request a design order <span>↗</span></a><a class="contact-email" href="mailto:<?= e($contactEmail) ?>"><?= e($contactEmail)</a></div></div></section>
</main>
<footer class="site-footer"><div class="footer-top"><a class="brand" href="index.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC</span></span></a><span>Independent design studio · Pakistan</span><a href="<?= e(whatsapp_url('Hello Hammad, I would like to discuss a design project.')) ?>" target="_blank" rel="noopener">WhatsApp ↗</a></div><div class="footer-bottom"><span>© <?= date('Y') ?> Hammad Graphic. All rights reserved.</span><span>Made with intention.</span><a href="admin/login.php">Admin</a></div></footer>
<div class="contact-widget" id="contactWidget">
<section class="contact-panel" id="contactPanel" aria-label="WhatsApp contact assistant" hidden>
<div class="contact-panel-head"><div class="bot-avatar">HG</div><div><strong>Hammad Graphic</strong><span>WhatsApp contact assistant</span></div><button type="button" class="panel-close" id="panelClose" aria-label="Close contact panel">×</button></div>
<div class="contact-order-cta"><span>Want to place a design order?</span><a href="order.php">Open the order form <b>↗</b></a></div>
<div class="contact-messages" id="contactMessages" aria-live="polite"></div>
<form class="contact-input" id="contactAssistantForm"><label class="sr-only" for="contactMessageInput">Your message</label><input id="contactMessageInput" type="text" maxlength="500" placeholder="Type a message…" autocomplete="off" required><button type="submit" aria-label="Send message">↑</button></form>
<div class="contact-panel-foot">Quick contact · No AI processing or message storage</div>
</section><button type="button" class="contact-launcher" id="contactLauncher" aria-expanded="false" aria-controls="contactPanel"><span class="launcher-symbol">✳</span><span>Need help?</span></button>
</div>
<script src="assets/js/app.js?v=1.0.0" defer></script><script src="assets/js/contact-assistant.js?v=1.0.0" defer></script>
<?php require __DIR__.'/includes/analytics-snippet.php'; ?>
</body></html>