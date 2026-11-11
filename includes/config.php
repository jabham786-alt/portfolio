<?php
/**
 * Hammad Graphic — InfinityFree configuration.
 *
 * Connection details below are taken from your InfinityFree screenshot.
 * IMPORTANT: Never put your real database password or INSTALL_KEY in public GitHub.
 * After downloading, enter both values in this file using InfinityFree File Manager.
 */
declare(strict_types=1);

define('SITE_NAME', 'Hammad Graphic');
define('OWNER_NAME', 'Hammad Ali');
define('OWNER_EMAIL', 'jabham786@gmail.com');
define('WHATSAPP_NUMBER', '923067115462');
define('SITE_URL', '');

/* InfinityFree > MySQL Databases */
define('DB_HOST', 'sql108.infinityfree.com');
define('DB_NAME', 'if0_43333038_hammadgraphic');
define('DB_USER', 'if0_43333038');
define('DB_PASS', 'PASTE_YOUR_INFINITYFREE_MYSQL_PASSWORD_HERE');

/*
 * Replace this with a private random value of at least 24 characters.
 * Do not share it or commit the edited file back to public GitHub.
 * The admin setup page will refuse to run until you replace this placeholder.
 */
define('INSTALL_KEY', 'REPLACE_WITH_YOUR_OWN_PRIVATE_RANDOM_KEY_24_CHARS_MIN');

define('MAX_UPLOAD_BYTES', 4 * 1024 * 1024);
define('UPLOAD_DIRECTORY', dirname(__DIR__) . '/storage/uploads');
