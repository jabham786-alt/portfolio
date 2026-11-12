<!doctype html>
<html lang="en" data-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#101018">
<meta name="description" content="Website design, complete website development and ongoing website support by Hammad Graphic. Clear one-time packages in PKR.">
<title>Website Design & Development Services — Hammad Graphic</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css?v=1.0.0">
<style>
.web-hero{padding:100px 0 75px;display:grid;grid-template-columns:1.1fr .9fr;gap:55px;align-items:center}
.web-hero h1{font:600 clamp(48px,6vw,76px)/.98 var(--display);letter-spacing:-4px;margin:24px 0}
.web-hero h1 em,.web-section-title em{font-style:normal;color:var(--accent)}
.web-hero-copy>p{max-width:580px;color:var(--muted);font-size:14px;line-height:1.9}
.web-hero-art{min-height:330px;background:var(--surface);border:1px solid var(--line);padding:28px;position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:space-between}
.web-hero-art:before{content:"";position:absolute;width:270px;height:270px;border:1px solid var(--accent);border-radius:50%;right:-70px;top:-65px;box-shadow:0 0 0 28px color-mix(in srgb,var(--accent) 8%,transparent),0 0 0 58px color-mix(in srgb,var(--accent) 5%,transparent)}
.web-hero-art small,.web-hero-art strong,.web-hero-art span{position:relative;z-index:1}
.web-hero-art small{font:700 9px var(--display);letter-spacing:2px;color:var(--muted)}
.web-hero-art strong{font:700 clamp(40px,5vw,64px)/.9 var(--display);letter-spacing:-3px;max-width:340px}
.web-hero-art strong em{font-style:normal;color:var(--accent)}
.web-hero-art span{font-size:10px;letter-spacing:1.5px;color:var(--muted)}
.web-packages{padding:35px 0 110px}
.web-section-title{font:600 clamp(38px,4.5vw,58px)/1.04 var(--display);letter-spacing:-2.5px;margin:18px 0}
.web-section-intro{max-width:560px;color:var(--muted);font-size:13px;line-height:1.9;margin-bottom:42px}
.web-package-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;align-items:stretch}
.web-package{background:var(--surface);border:1px solid var(--line);padding:27px;display:flex;flex-direction:column;min-height:510px;position:relative}
.web-package.is-featured{border-color:var(--accent);background:linear-gradient(155deg,var(--surface),color-mix(in srgb,var(--accent) 9%,var(--surface)))}
.web-package-kicker{font:700 9px var(--display);letter-spacing:1.5px;color:var(--accent)}
.web-package h3{font:600 22px/1.2 var(--display);margin:24px 0 10px}
.web-package-price{font:700 clamp(32px,3.1vw,43px)/1.1 var(--display);letter-spacing:-1.5px;margin:8px 0 5px}
.web-package-price small{display:block;font:500 10px var(--body);letter-spacing:.4px;color:var(--muted);margin-top:8px}
.web-package-desc{font-size:12px;line-height:1.8;color:var(--muted);min-height:65px;margin:15px 0 18px}
.web-feature-list{list-style:none;padding:0;margin:0 0 28px;display:grid;gap:12px}
.web-feature-list li{font-size:11px;line-height:1.65;display:flex;gap:9px}
.web-feature-list li:before{content:"✓";color:var(--accent);font-weight:700}
.web-package .button{width:100%;margin-top:auto}
.web-note{margin-top:25px;padding:18px 20px;border:1px solid var(--line);color:var(--muted);font-size:11px;line-height:1.8}
.web-process{background:var(--surface);border-block:1px solid var(--line);padding:75px 0}
.web-process-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:30px;margin-top:35px}
.web-process-item{border-top:1px solid var(--line);padding-top:20px}
.web-process-item b{font:700 11px var(--display);color:var(--accent);letter-spacing:1px}
.web-process-item h3{font:600 17px var(--display);margin:14px 0 8px}
.web-process-item p{font-size:11px;line-height:1.8;color:var(--muted);margin:0}
.web-cta{padding:90px 0;display:flex;justify-content:space-between;align-items:center;gap:35px}
.web-cta h2{font:600 clamp(38px,4.5vw,58px)/1.02 var(--display);letter-spacing:-2.5px;margin:18px 0}
.web-cta p{max-width:460px;color:var(--muted);font-size:12px;line-height:1.8}
.web-cta-actions{display:flex;gap:12px;flex-wrap:wrap}
.web-footer{border-top:1px solid var(--line);padding:25px 0;display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap;color:var(--muted);font-size:10px}
.web-footer a:hover{color:var(--accent)}
@media(max-width:900px){.web-hero{grid-template-columns:1fr;gap:30px;padding-top:70px}.web-hero-art{min-height:260px}.web-package-grid{grid-template-columns:1fr 1fr}.web-package:last-child{grid-column:1/-1}.web-package{min-height:0}}
@media(max-width:650px){.section-wrap{width:min(100% - 36px,1180px)}.web-hero{padding:55px 0 50px}.web-hero h1{letter-spacing:-2.5px}.web-package-grid,.web-process-grid{grid-template-columns:1fr}.web-package:last-child{grid-column:auto}.web-package{padding:23px}.web-package-desc{min-height:0}.web-cta{padding:65px 0;display:block}.web-cta-actions{margin-top:25px}.web-footer{display:grid}}
</style>
</head>
<body>
<header class="site-header">
<a class="brand" href="../index.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC</span></span></a>
<button class="menu-toggle" id="menuToggle" aria-label="Open navigation" aria-expanded="false"><span></span><span></span></button>
<nav class="main-nav" id="mainNav">
<a href="../index.php#services">Graphic services</a>
<a href="#packages">Web packages</a>
<a href="../index.php#work">Portfolio</a>
<a href="../index.php#about">About</a>
<a href="../index.php#contact">Contact</a>
<a class="nav-order" href="../order.php">Design order <span>↗</span></a>
</nav>
<button class="theme-toggle" id="themeToggle" type="button" aria-label="Toggle colour theme"><span class="theme-icon">☼</span></button>
</header>
<main>
<section class="section-wrap web-hero">
<div class="web-hero-copy">
<div class="eyebrow"><span class="eyebrow-line"></span> HAMMAD GRAPHIC / WEB SERVICES</div>
<h1>Your business<br>deserves a better<br><em>website.</em></h1>
<p>From a clean website design to a complete coded website, choose the package that matches your goal. Need continued care after launch? The complete package also includes ongoing support and backup assistance.</p>
<div class="hero-actions"><a class="button button-primary" href="#packages">Explore packages <span>↓</span></a><a class="text-link" href="https://wa.me/923067115462?text=Hello%20Hammad%2C%20I%20want%20to%20discuss%20a%20website%20project." target="_blank" rel="noopener">Ask on WhatsApp <span>↗</span></a></div>
</div>
<div class="web-hero-art"><small>DESIGN · DEVELOPMENT · SUPPORT</small><strong>MAKE YOUR<br>ONLINE PRESENCE<br><em>WORK.</em></strong><span>THOUGHTFUL DIGITAL EXPERIENCES BY HAMMAD GRAPHIC</span></div>
</section>
<section class="section-wrap web-packages" id="packages">
<div class="eyebrow">CLEAR ONE-TIME PACKAGE PRICING</div>
<h2 class="web-section-title">Choose the right<br><em>level of service.</em></h2>
<p class="web-section-intro">Each package is designed for a different stage of your business. Select the service you need and contact me to discuss your website requirements.</p>
<div class="web-package-grid">
<article class="web-package">
<span class="web-package-kicker">01 / DESIGN</span>
<h3>Website Design</h3>
<div class="web-package-price">PKR 5,000<small>One-time fee</small></div>
<p class="web-package-desc">A professional website look and page layout planned around your brand and customers.</p>
<ul class="web-feature-list">
<li>Website UI / UX design</li>
<li>Page layout and visual structure</li>
<li>Colour and typography selection</li>
<li>Desktop and mobile design concept</li>
<li>Design presentation for approval</li>
</ul>
<a class="button button-primary" href="https://wa.me/923067115462?text=Hello%20Hammad%2C%20I%20want%20the%20PKR%205%2C000%20Website%20Design%20package." target="_blank" rel="noopener">Select design package <span>↗</span></a>
</article>
<article class="web-package">
<span class="web-package-kicker">02 / COMPLETE CODING</span>
<h3>Full Website Development</h3>
<div class="web-package-price">PKR 15,000<small>One-time fee</small></div>
<p class="web-package-desc">A complete website designed and developed with working code, ready to represent your business online.</p>
<ul class="web-feature-list">
<li>Website design and coding</li>
<li>Complete agreed website pages</li>
<li>Responsive mobile and desktop layout</li>
<li>Navigation and contact functionality</li>
<li>Testing and website handover</li>
</ul>
<a class="button button-primary" href="https://wa.me/923067115462?text=Hello%20Hammad%2C%20I%20want%20the%20PKR%2015%2C000%20Full%20Website%20Development%20package." target="_blank" rel="noopener">Select development package <span>↗</span></a>
</article>
<article class="web-package is-featured">
<span class="web-package-kicker">03 / COMPLETE CARE PACKAGE</span>
<h3>Website + 1-Year Support & Backup</h3>
<div class="web-package-price">PKR 30,000<small>One-time fee · ongoing support included</small></div>
<p class="web-package-desc">A complete website delivered live, with one year of website support and backup assistance to help keep it maintained and resolve website-related issues.</p>
<ul class="web-feature-list">
<li>Complete website design and coding</li>
<li>Responsive pages and agreed features</li>
<li>One full year of website support</li>
<li>One full year of backup support</li>
<li>Help resolving website errors and issues during the support year</li>
<li>Agreed website updates during the support year</li>
</ul>
<a class="button button-primary" href="https://wa.me/923067115462?text=Hello%20Hammad%2C%20I%20want%20the%20PKR%2030%2C000%20Complete%20Website%20with%20Full%20Support%20and%20Backup%20package." target="_blank" rel="noopener">Select complete package <span>↗</span></a>
</article>
</div>
<p class="web-note">All package prices are one-time fees in PKR. Before work begins, the required pages, features, content, delivery plan and support arrangements will be discussed and confirmed with you. Domain registration, hosting charges and paid third-party services are not included unless specifically agreed.</p>
</section>
<section class="web-process"><div class="section-wrap">
<div class="eyebrow">HOW THE PROJECT WORKS</div>
<h2 class="web-section-title">A clear path from idea<br><em>to launch.</em></h2>
<div class="web-process-grid">
<article class="web-process-item"><b>STEP 01</b><h3>Discuss your requirements</h3><p>Share your business details, required pages, features and preferred package.</p></article>
<article class="web-process-item"><b>STEP 02</b><h3>Design and development</h3><p>The agreed website is designed and built, with progress reviewed along the way.</p></article>
<article class="web-process-item"><b>STEP 03</b><h3>Review and handover</h3><p>We test the website, complete the agreed handover and arrange support according to your package.</p></article>
</div>
</div></section>
<section class="section-wrap web-cta">
<div><div class="eyebrow">READY TO START?</div><h2>Let’s build your<br><em>website.</em></h2><p>Tell me what kind of website you need. I’ll discuss the right package and project details with you on WhatsApp.</p></div>
<div class="web-cta-actions"><a class="button button-primary" href="https://wa.me/923067115462?text=Hello%20Hammad%2C%20I%20would%20like%20a%20website%20quotation." target="_blank" rel="noopener">Contact on WhatsApp <span>↗</span></a><a class="button" style="border-color:var(--line)" href="../index.php">Back to Hammad Graphic</a></div>
</section>
</main>
<footer class="section-wrap web-footer"><a class="brand" href="../index.php"><span class="brand-mark">H<span>G</span></span><span class="brand-name">HAMMAD<span>GRAPHIC</span></span></a><span>© 2026 Hammad Graphic · Pakistan</span><a href="mailto:jabham786@gmail.com">jabham786@gmail.com</a></footer>
<script src="../assets/js/app.js?v=1.0.0" defer></script>
</body>
</html>
