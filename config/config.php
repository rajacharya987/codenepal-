<?php
/**
 * CodeNepal Configuration File
 * Contains all application constants and settings
 */

// Start output buffering to prevent header issues
// This must be the first thing in the file
ob_start();

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'codenepal');

// Site Configuration
define('SITE_URL', 'http://localhost/');
define('SITE_NAME', 'CodeNepal');

// Directory Paths
define('ROOT_PATH', dirname(__DIR__));
define('UPLOAD_DIR', ROOT_PATH . '/uploads/');
define('TEMP_DIR', ROOT_PATH . '/temp/');

// File Upload Settings
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// Session Configuration
define('SESSION_LIFETIME', 86400); // 24 hours in seconds
define('SESSION_NAME', 'codenepal_session');

// Code Execution Settings
define('CODE_TIMEOUT', 3); // seconds
define('MAX_CODE_LENGTH', 10000); // characters
define('PYTHON_PATH', 'python');
define('NODE_PATH', 'node');
define('GCC_PATH', 'g++');

// Security Settings
define('BCRYPT_COST', 10);
define('CSRF_TOKEN_NAME', 'csrf_token');
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 minutes

// Pagination
define('ITEMS_PER_PAGE', 12);

// AI Configuration (Gemini)
define('GEMINI_API_KEY', 'AIzaSyA6HhPjA-_m6tbSV-lE3uuzUPeg1b99CNk');
define('GEMINI_MODEL', 'gemini-2.0-flash-exp');
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/' . GEMINI_MODEL . ':generateContent');

// Error Reporting (set to false in production)
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT_PATH . '/logs/php_errors.log');
}

// Timezone
date_default_timezone_set('Asia/Kathmandu');

// Start session with secure settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_lifetime', SESSION_LIFETIME);

if (!isset($_SESSION)) {
    session_name(SESSION_NAME);
    session_start();
}
