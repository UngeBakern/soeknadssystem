<?php
// Database configuration (for future MySQL integration)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'soeknadssystem');

// Session settings (must be set before session_start)
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS

// Application settings
define('APP_NAME', 'Hjelpelærer Søknadssystem');
define('APP_URL', 'http://localhost/soeknadssystem');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Europe/Oslo');

// Include data files (array-based storage initially)
require_once __DIR__ . '/../data/users.php';
require_once __DIR__ . '/../data/jobs.php';
require_once __DIR__ . '/../data/applications.php';
?>