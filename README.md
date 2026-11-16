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

## Customer accounts (feature branch)

The customer account feature is developed on `feature/customer-accounts-payments`; it is not merged into the production branch and has not been deployed to InfinityFree.

1. Back up the live website files and database.
2. Confirm `database/customer-accounts-payments-migration.sql` has been applied once. If not, apply it first.
3. Run `database/customer-manual-approval-migration.sql` once in phpMyAdmin. It changes new registrations to admin approval and creates the one-time activation token table. Do not rerun it after successful completion.
4. Upload the branch's changed/new PHP files to their matching paths. Do not overwrite the live `includes/config.php`; preserve the live database credentials and INSTALL_KEY.
5. Set the live `SITE_URL` constant in `includes/config.php` to the canonical HTTPS domain.
6. Customers register at `/register.php`; they remain pending until admin approval.
7. Admin signs in at `/admin/login.php` and manages approvals at `/admin/customers.php`. Approving creates a one-use link valid for 48 hours. The admin manually sends it via WhatsApp; no API or automatic sending is used.
8. Customer activates at `/activate-account.php`, then signs in at `/login.php` and uses `/customer/index.php`.
9. Existing active accounts remain active. Existing historical orders remain unassigned unless safely linked after verifying ownership.

Payment integration remains a separate foundation. Do not mark a payment paid from a browser redirect or screenshot. InfinityFree Free Hosting blocks inbound third-party webhook callbacks; verified payment confirmation needs a trusted webhook relay or webhook-capable payment endpoint.


## Project File Structure

> **Application source branch:** `feature/web-designing-services-page`  
> The production website files currently live on this branch. The repository's `main` branch is currently a minimal landing branch; this tree documents the application branch so visitors can see where the website source files are organized.

```text
portfolio/
├── .gitignore
├── .htaccess
├── README.md
├── LICENSE
├── ANALYTICS-SETUP-UR.md
├── CONTENT-ZOOM-SETUP-UR.md
├── CUSTOMER-ACCOUNTS-PAYMENTS-PLAN-UR.md
├── DEPLOYMENT-CHECKLIST-UR.md
├── INFINITYFREE-SETUP-UR.md
├── SAFEPAY-SETUP-UR.md
│
├── index.php
├── activate-account.php
├── analytics-event.php
├── login.php
├── logout.php
├── order.php
├── payment.php
├── register.php
├── submit-order.php
├── website-services.php
├── zoom-meeting.php
│
├── admin/
│   ├── analytics.php
│   ├── customers.php
│   ├── diagnose.php
│   ├── download.php
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   ├── order.php
│   ├── payment-proof.php
│   ├── payment-settings.php
│   ├── portfolio.php
│   ├── quote.php
│   ├── reset-password.php
│   ├── setup.php
│   ├── site-editor.php
│   └── zoom-meetings.php
│
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── images/
│   │   └── PHOTO-INSTRUCTIONS.txt
│   ├── js/
│   │   ├── analytics.js
│   │   ├── app.js
│   │   └── contact-assistant.js
│   └── uploads/
│       ├── portfolio/
│       │   └── .htaccess
│       └── site/
│           └── .htaccess
│
├── customer/
│   └── index.php
│
├── database/
│   ├── analytics-migration.sql
│   ├── content-migration.sql
│   ├── customer-accounts-payments-migration.sql
│   ├── customer-manual-approval-migration.sql
│   ├── payment-migration.sql
│   └── schema.sql
│
├── includes/
│   ├── .htaccess
│   ├── analytics-snippet.php
│   ├── bootstrap.php
│   ├── config.php
│   ├── customer-activation.php
│   └── customer-auth.php
│
├── storage/
│   ├── .htaccess
│   └── uploads/
│       ├── .htaccess
│       └── index.html
│
└── web-designing/
    └── index.php
```

### Folder Guide

| Folder / file | Purpose |
|---|---|
| `admin/` | Private admin dashboard and management tools |
| `assets/` | CSS, JavaScript, images and website uploads |
| `customer/` | Customer account dashboard |
| `database/` | Database schema and SQL migration files |
| `includes/` | Shared PHP bootstrap, configuration and authentication |
| `storage/` | Protected customer upload storage |
| `web-designing/` | Web designing service page |
| Root PHP files | Public website pages, registration, login, orders and payment flow |

> **Note:** This is the tracked source tree for the branch named above, based on the files currently present in GitHub. Runtime-created customer uploads, database records, and hosting-only files are not listed. The Messenger package is not included in this tree until its files are added to the repository. The live hosting copy may contain additional files or newer changes not yet committed to GitHub.
