<?php
declare(strict_types=1);

// Send every visit to the main website URL to the customer entry page.
header('Location: welcome.php', true, 302);
exit;
