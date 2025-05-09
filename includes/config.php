<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'article_system');
define('DB_USER', 'root');
define('DB_PASS', '');

// Social Media API Keys
define('FACEBOOK_APP_ID', '');
define('FACEBOOK_APP_SECRET', '');
define('TWITTER_API_KEY', '');
define('TWITTER_API_SECRET', '');

// Website configuration
define('SITE_NAME', 'ArticleHub');
define('SITE_URL', 'http://localhost/new');
define('UPLOAD_PATH', __DIR__ . '/../assets/uploads/');
define('PROFILE_IMAGE_PATH', __DIR__ . '/../assets/uploads/profiles/');
define('ARTICLE_IMAGE_PATH', __DIR__ . '/../assets/uploads/articles/');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Timezone
date_default_timezone_set('UTC'); 