<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'l1j_remastered');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site configuration
define('SITE_NAME', 'LineageII Remastered Database');
define('SITE_URL', 'http://localhost/lineage-db');

// Pagination settings
define('ITEMS_PER_PAGE', 25);

// Admin settings
define('ADMIN_EMAIL', 'admin@example.com');

// Error reporting settings
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set default timezone
date_default_timezone_set('UTC');
?>
