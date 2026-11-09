<!doctype html>
<html lang="en" data-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="theme-color" content="#101018">
<meta name="description" content="Welcome to Hammad Graphic. Register for a customer account or log in to continue.">
<title>Welcome — Hammad Graphic</title>
<link rel="stylesheet" href="assets/css/style.css?v=1.0.0">
<style>
.auth-welcome{min-height:100vh;display:grid;place-items:center;padding:28px 16px;background:var(--bg)}
.auth-welcome-card{width:min(100%,560px);padding:clamp(28px,6vw,54px);background:var(--surface);border:1px solid var(--line);box-shadow:0 24px 80px rgba(0,0,0,.22)}
.auth-welcome-card .brand{display:inline-flex;margin-bottom:34px}
.auth-welcome-card h1{font:600 clamp(34px,7vw,52px)/1.05 var(--display);letter-spacing:-2px;margin:12px 0 14px}
.auth-welcome-card h1 em{font-style:normal;color:var(--accent)}
.auth-welcome-card .intro{color:var(--muted);font-size:14px;line-height:1.8;margin:0 0 28px}
.auth-actions{display:grid;gap:13px}
.auth-actions .button{width:100%;justify-content:center;min-height:54px}
.auth-login{background:transparent;color:var(--text);border:1px solid var(--line)}
.auth-login:hover{border-color:var(--accent);color:var(--accent)}
.auth-divider{display:flex;align-items:center;gap:12px;color:var(--muted);font-size:10px;letter-spacing:2px;margin:5px 0}
.auth-divider:before,.auth-divider:after{content:"";height:1px;flex:1;background:var(--line)}
.auth-help{font-size:11px;line-height:1.8;color:var(--muted);margin:24px 0 0}
.auth-help a{color:var(--accent)}
.auth-admin{display:block;margin-top:18px;font-size:11px;color:var(--muted);text-decoration:none}
.auth-admin:hover{color:var(--accent)}
</style>
</head>
<body>
<main class="auth-welcome">
<section class="auth-welcome-card">
<a class="brand" href="welcome.php" aria-label="Hammad Graphic home">
<span class="brand-mark">H<span>G</span></span>
<span class="brand-name">HAMMAD<span>GRAPHIC</span></span>
</a>
<div class="eyebrow">CUSTOMER PORTAL</div>
<h1>Welcome to <em>Hammad Graphic.</em></h1>
<p class="intro">Create your customer account to request designs and manage your orders. Already have an account? Log in below.</p>
<div class="auth-actions">
<a class="button button-primary" href="register.php">Register / Create Account <span>↗</span></a>
<div class="auth-divider">ALREADY A CUSTOMER?</div>
<a class="button auth-login" href="login.php">Login to Your Account <span>↗</span></a>
</div>
<p class="auth-help">New registrations are reviewed by the admin. After approval, an account activation link is sent manually through WhatsApp.</p>
<a class="auth-admin" href="admin/login.php">Admin login ↗</a>
</section>
</main>
</body>
</html>
