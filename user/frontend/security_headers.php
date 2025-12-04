<?php
// Basic error handling
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Essential security headers only
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');  // Changed from DENY to SAMEORIGIN
header('X-XSS-Protection: 1; mode=block');
header('X-Powered-By:');

// Very permissive CSP to ensure functionality
header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' data: https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' https:; style-src 'self' 'unsafe-inline' https:; img-src 'self' data: https:; font-src 'self' https:;");

// Basic cookie security
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0);  // Off for localhost
ini_set('session.use_strict_mode', 1);

// Server signature hiding
ini_set('expose_php', 0);
?>