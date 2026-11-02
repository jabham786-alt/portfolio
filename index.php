<?php
require_once __DIR__ . '/includes/bootstrap.php';
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
</head>
<body>
<header class="site-header">
<a class="brand" href="index.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC</span></span></a>
<button class="menu-toggle" id="menuToggle" aria-label="Open navigation" aria-expanded="false"><span></span><span></span></button>
<nav class="main-nav" id="mainNav"><a href="#services">Services</a><a href="#work">Selected work</a><a href="#about">About</a><a href="#contact">Contact</a><a class="nav-order" href="order.php">Start a project <span>↗</span></a></nav>
<button class="theme-toggle" id="themeToggle" type="button" aria-label="Toggle colour theme"><span class="theme-icon">☼</span></button>
</header>
<main>
<section class="hero section-wrap">
<div class="hero-copy">
<div class="eyebrow"><span class="eyebrow-line"></span> INDEPENDENT GRAPHIC DESIGN STUDIO</div>
<h1>Good design<br>makes you <em>look twice.</em></h1>
<p class="hero-lead">Thoughtful visuals for brands, businesses and people with something to say. Let’s turn your next idea into a design that feels unmistakably yours.</p>
<div class="hero-actions"><a class="button button-primary" href="order.php">Start a project <span>↗</span></a><a class="text-link" href="#work">Explore selected work <span>↓</span></a></div>
<div class="hero-meta"><div><strong>HAMMAD ALI</strong><span>Graphic Designer</span></div><div class="meta-divider"></div><div><strong>BASED IN PAKISTAN</strong><span>Working with ideas everywhere</span></div></div>
</div>
<div class="hero-art" aria-label="Abstract graphic design composition">
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
<section class="section-wrap about-section section-block" id="about">
<div class="about-visual"><div class="about-monogram">H<span>G</span></div><div class="about-note">GOOD IDEAS<br>DESERVE<br>GOOD DESIGN.</div><div class="about-orbit"></div></div>
<div class="about-copy"><div class="eyebrow">A LITTLE ABOUT THE DESIGNER</div><h2>Hello, I’m<br><em>Hammad Ali.</em></h2><p class="about-intro">I’m a graphic designer behind Hammad Graphic. I create visual work for businesses, creators and growing brands — with a focus on clarity, personality and the details that make a design feel right.</p><p>Whether you need a new identity, a social media set or a single standout graphic, tell me what you’re building and we can shape the right direction together.</p><div class="skill-tags"><span>Adobe Photoshop</span><span>Adobe Illustrator</span><span>Social media design</span><span>Brand visuals</span><span>Print design</span></div><a href="order.php" class="text-link">Tell me about your project <span>↗</span></a></div>
</section>
<section class="contact-band" id="contact"><div class="section-wrap contact-inner"><div><div class="eyebrow">HAVE SOMETHING IN MIND?</div><h2>Let’s make it<br><em>happen.</em></h2></div><div class="contact-side"><p>Share a little about your idea. I’ll review the brief and follow up to discuss the next step.</p><a class="button button-light" href="order.php">Request a design order <span>↗</span></a><a class="contact-email" href="mailto:jabham786@gmail.com">jabham786@gmail.com</a></div></div></section>
</main>
<footer class="site-footer"><div class="footer-top"><a class="brand" href="index.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC</span></span></a><span>Independent design studio · Pakistan</span><a href="<?= e(whatsapp_url('Hello Hammad, I would like to discuss a design project.')) ?>" target="_blank" rel="noopener">WhatsApp ↗</a></div><div class="footer-bottom"><span>© <?= date('Y') ?> Hammad Graphic. All rights reserved.</span><span>Made with intention.</span><a href="admin/login.php">Admin</a></div></footer>
<div class="contact-widget" id="contactWidget">
<section class="contact-panel" id="contactPanel" aria-label="WhatsApp contact assistant" hidden>
<div class="contact-panel-head"><div class="bot-avatar">HG</div><div><strong>Hammad Graphic</strong><span>WhatsApp contact assistant</span></div><button type="button" class="panel-close" id="panelClose" aria-label="Close contact panel">×</button></div>
<div class="contact-messages" id="contactMessages" aria-live="polite"></div>
<form class="contact-input" id="contactAssistantForm"><label class="sr-only" for="contactMessageInput">Your message</label><input id="contactMessageInput" type="text" maxlength="500" placeholder="Type a message…" autocomplete="off" required><button type="submit" aria-label="Send message">↑</button></form>
<div class="contact-panel-foot">Quick contact · No AI processing or message storage</div>
</section><button type="button" class="contact-launcher" id="contactLauncher" aria-expanded="false" aria-controls="contactPanel"><span class="launcher-symbol">✳</span><span>Need help?</span></button>
</div>
<script src="assets/js/app.js?v=1.0.0" defer></script><script src="assets/js/contact-assistant.js?v=1.0.0" defer></script>
</body></html>