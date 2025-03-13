<?php
// Configuration settings
define('UPLOAD_MAX_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);
define('UPLOAD_PATH', __DIR__ . '/uploads/');
define('FLAG_PATH', '/var/hidden/level1/level2/secret_flag.txt');
define('MAX_FILENAME_LENGTH', 255);

// Security headers
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff"); 