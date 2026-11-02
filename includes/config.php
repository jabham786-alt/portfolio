<?php
/**
 * Hammad Graphic configuration.
 * Fill in the database details from InfinityFree Control Panel > MySQL Databases.
 * Never upload a real database password to a public Git repository.
 */
declare(strict_types=1);

define('SITE_NAME', 'Hammad Graphic');
define('OWNER_NAME', 'Hammad Ali');
define('OWNER_EMAIL', 'jabham786@gmail.com');
define('WHATSAPP_NUMBER', '923067115462');
define('SITE_URL', '');

define('DB_HOST', 'YOUR_INFINITYFREE_MYSQL_HOST');
define('DB_NAME', 'YOUR_DATABASE_NAME');
define('DB_USER', 'YOUR_DATABASE_USERNAME');
define('DB_PASS', 'CHANGE_THIS_DATABASE_PASSWORD');

define('INSTALL_KEY', 'CHANGE-THIS-TO-A-LONG-RANDOM-ONE-TIME-KEY');
define('MAX_UPLOAD_BYTES', 4 * 1024 * 1024);
define('UPLOAD_DIRECTORY', dirname(__DIR__) . '/storage/uploads');
