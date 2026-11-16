# portfolio
"Local AI Assistant with Ollama" portfolio website


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
