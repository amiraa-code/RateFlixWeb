<?php
session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
require_once "../backend/include/db.php"; 

$errors = [];
$referrer = $_GET['return_to'] ?? $_GET['referrer'] ?? $_SERVER['HTTP_REFERER'] ?? 'index.php';

// Check for valid remember-me token
if (empty($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt = $conn->prepare("SELECT user_id, username FROM users WHERE remember_token = ? AND remember_expires > NOW() LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $username);
            $stmt->fetch();
            $_SESSION['user_id'] = (int)$user_id;
            $_SESSION['username'] = $username;
        }
        $stmt->close();
    }
}


// Sanitize referrer to prevent open redirect
if (!preg_match('~^https?://~i', $referrer)) {
    $referrer = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $referrer;
}

if (!filter_var($referrer, FILTER_VALIDATE_URL)) {
    $referrer = 'index.php';
} elseif (strpos($referrer, $_SERVER['HTTP_HOST']) === false) {
    $referrer = 'index.php';
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = trim($_POST["password"] ?? '');
    $posted_referrer = $_POST["return_to"] ?? $referrer;

    // Re-validate posted referrer
    if (!preg_match('~^https?://~i', $posted_referrer)) {
        $posted_referrer = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $posted_referrer;
    }

    if (!filter_var($posted_referrer, FILTER_VALIDATE_URL)) {
        $posted_referrer = 'index.php';
    } elseif (strpos($posted_referrer, $_SERVER['HTTP_HOST']) === false) {
        $posted_referrer = 'index.php';
    }

    if ($username === "") $errors[] = "Username is required.";
    if ($password === "") $errors[] = "Password is required.";

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE username = ? LIMIT 1");
        if (!$stmt) {
            $errors[] = "Database error. Please try again.";
            error_log('Login prepare failed: ' . $conn->error);
        } else {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($user_id, $dbUsername, $dbPassword);
                $stmt->fetch();

                if (password_verify($password, $dbPassword)) {
                    $_SESSION["user_id"] = (int)$user_id;
                    $_SESSION["username"] = $dbUsername;

                    // Handle Remember Me
                    if (isset($_POST['remember']) && $_POST['remember'] === 'on') {
                        $token = bin2hex(random_bytes(32));
                        $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
                        $update_stmt = $conn->prepare("UPDATE users SET remember_token = ?, remember_expires = ? WHERE user_id = ?");
                        if ($update_stmt) {
                            $update_stmt->bind_param("ssi", $token, $expires, $user_id);
                            $update_stmt->execute();
                            $update_stmt->close();
                            setcookie('remember_token', $token, strtotime('+30 days'), '/', '', true, true);
                        }
                    }

                    // Always redirect to home page after login
                    header("Location: /RATEFLIXWEB/user/frontend/index.php");
                    exit;
                } else {
                    $errors[] = "Invalid username or password.";
                }
            } else {
                $errors[] = "Invalid username or password.";
            }

            $stmt->close();
        }
    }
}

// If user is already logged in, redirect to return_to page
if (!empty($_SESSION['user_id'])) {
    $return_url = $_GET['return_to'] ?? $_GET['referrer'] ?? $_SERVER['HTTP_REFERER'] ?? 'index.php';
    // thid adds scheme and host if missing
    if (!preg_match('~^https?://~i', $return_url)) {
        $return_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $return_url;
    }
    if (!filter_var($return_url, FILTER_VALIDATE_URL)) {
        $return_url = 'index.php';
    } elseif (strpos($return_url, $_SERVER['HTTP_HOST']) === false) {
        $return_url = 'index.php';
    }
    header("Location: " . htmlspecialchars($return_url, ENT_QUOTES, 'UTF-8'));
    exit;
}


$twig = require __DIR__ . '/twig_init.php';
$twig->display('login.twig', [
    'errors' => $errors,
    // For header.twig dynamic user info
    'username' => isset($_SESSION['username']) ? $_SESSION['username'] : (isset($username) ? $username : ''),
    'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
    // For header.twig login/register links
    'return_to' => $referrer,
    // For login form value
    'form_username' => isset($username) ? $username : ''
]);
