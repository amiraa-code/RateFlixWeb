<?php
session_start();
header('X-Content-Type-Options: nosniff');
require_once "../backend/include/db.php";

// Clear remember-me token from database
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("UPDATE users SET remember_token = NULL, remember_expires = NULL WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Properly destroy session
session_unset();
session_destroy();

// Clear session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Clear remember-me cookie
setcookie('remember_token', '', time() - 42000, '/', '', true, true);
header("Location: index.php");
exit;
