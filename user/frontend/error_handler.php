<?php
// Simple error handler for production
function custom_error_handler($errno, $errstr, $errfile, $errline) {
    // Log the error
    error_log("Error: $errstr in $errfile on line $errline");
    
    // Show generic message to user
    if (!headers_sent()) {
        http_response_code(500);
    }
    
    echo "<div style='text-align: center; margin: 50px; font-family: Arial;'>";
    echo "<h2>Something went wrong</h2>";
    echo "<p>We're sorry, but something unexpected happened. Please try again later.</p>";
    echo "<a href='index.php'>Return to Home</a>";
    echo "</div>";
    exit;
}
// Set custom error handler for production
if (!defined('DEVELOPMENT_MODE')) {
    set_error_handler('custom_error_handler');
}
?>