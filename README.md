# Hammad Graphic — order-taking design agency website

PHP + MySQL agency website for Hammad Ali. Built for simple shared hosting such as InfinityFree. No Node.js, framework runtime or background service is required.

## Included

- Responsive home page with Services, concept Work, About and Contact sections.
- Dark / light mode saved in the visitor's browser.
- Order form: customer name, email, WhatsApp, service, estimated budget, deadline, project brief and optional JPG/PNG/WEBP/PDF attachment (4 MB maximum).
- Server-side validation, prepared SQL statements, CSRF token, honeypot and request throttling.
- MySQL order records with unique reference numbers.
- Admin installer, password-hashed sign-in, dashboard, order-status management, private notes and protected attachment downloads.
- Contact-assistant widget that replies to every submitted message with exactly: **Please contact us on WhatsApp: +923067115462**. It does not use an AI model, send text to a server or store messages. It is a fixed browser-side contact widget, not an AI chatbot.

## Local setup (XAMPP on Windows)

1. Install XAMPP with PHP 8.1+ and MySQL/MariaDB.
2. Copy this repository into C:\xampp\htdocs\hammad-graphic.
3. Start Apache and MySQL.
4. Create a database named hammad_graphic in phpMyAdmin.
5. Edit includes/config.php:
   - DB_HOST: localhost
   - DB_NAME: hammad_graphic
   - DB_USER: root
   - DB_PASS: blank only for a default local XAMPP setup
   - SITE_URL: http://localhost/hammad-graphic
   - Replace INSTALL_KEY with a random value of 24+ characters.
6. Open http://localhost/hammad-graphic/admin/setup.php; enter the same install key and create your admin account. Use a unique password of at least 12 characters.
7. Delete admin/setup.php after the first admin is created.
8. Test the home page, order form, admin login, status update and attachment download.

## InfinityFree deployment

1. Create an InfinityFree hosting account and add a domain or free subdomain.
2. In the hosting control panel, open MySQL Databases and create a database. Copy the exact database host, full database name, username and password. The host is not necessarily localhost.
3. Edit includes/config.php locally and fill in DB_HOST, DB_NAME, DB_USER, DB_PASS. Set SITE_URL to your HTTPS domain when ready. Set a long random INSTALL_KEY.
4. Upload the contents of this repository to the document-root directory shown for your domain in the InfinityFree Domains page (often htdocs; use the directory listed for your specific domain). Use FTP or File Manager. Keep all folders in their original structure.
5. Ensure storage/uploads exists and is writable by PHP. Its .htaccess blocks direct public access; admin downloads are served only after sign-in.
6. Visit https://YOUR-DOMAIN/admin/setup.php once and create your private admin account.
7. Immediately delete admin/setup.php from the live hosting account using FTP/File Manager.
8. Enable SSL / HTTPS in the hosting panel. Test the public website and /admin/login.php over HTTPS.
9. Place a test order and confirm it appears in the admin dashboard. Change its status, add a private note and test an attachment download before advertising the website.

## Important hosting limitations

- InfinityFree currently advertises PHP 8.4 and MySQL 8.0 / MariaDB 11.4 on its free hosting, but hosting quotas and policy still apply.
- Do not rely on PHP mail() for notifications on InfinityFree free hosting. New orders are saved to the database and appear in the admin panel. External SMTP can be added later; keep SMTP credentials private and do not commit them to GitHub.
- The contact assistant is browser-side and only displays the fixed WhatsApp instruction. It is not a server-hosted live chat script or generative AI bot.
- The three visible Work tiles are labelled concept compositions, not claimed client projects. Replace them with your own approved design images before presenting them as completed client commissions.
- Free shared hosting cannot guarantee zero downtime or production-grade business service. Run the complete test checklist on your actual hosting account before using the site for customer orders.

## Security

- Never commit actual database credentials, API keys, or SMTP passwords.
- Delete admin/setup.php after first-time installation.
- Use HTTPS and a unique strong admin password.
- Uploaded files are stored under storage/uploads with random names and cannot be opened directly from the public web.
- Back up the database and customer attachments regularly.
