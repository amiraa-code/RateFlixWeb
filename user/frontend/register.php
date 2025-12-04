<?php
require_once 'security_headers.php';
// require_once 'error_handler.php'; // Temporarily disabled
session_start();
require_once "../backend/include/db.php";

// Generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
$captcha_errors = [];
$referrer = $_GET['return_to'] ?? $_GET['referrer'] ?? $_SERVER['HTTP_REFERER'] ?? 'index.php';

// Sanitize referrer to prevent open redirect
if (!preg_match('~^https?://~i', $referrer)) {
    $referrer = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $referrer;
}

if (!filter_var($referrer, FILTER_VALIDATE_URL)) {
    $referrer = 'index.php';
} elseif (strpos($referrer, $_SERVER['HTTP_HOST']) === false) {
    $referrer = 'index.php';
}

// If user is already logged in, redirect
if (!empty($_SESSION['user_id'])) {
    header("Location: " . htmlspecialchars($referrer, ENT_QUOTES, 'UTF-8'));
    exit;
}

// Generate emoji captcha
$emojis = [
    'cat' => '🐱',
    'dog' => '🐶',
    'car' => '🚗',
    'tree' => '🌳',
    'house' => '🏠',
];
$keys = array_keys($emojis);
shuffle($keys);
if (!isset($_SESSION['captcha_expected']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $prompt_key = $keys[array_rand($keys)];
    $_SESSION['captcha_expected'] = $prompt_key;
} else {
    $prompt_key = $_SESSION['captcha_expected'];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // CSRF Token Validation
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = "Invalid request. Please try again.";
    } else {
        $email = trim($_POST["email"] ?? '');
        $username = trim($_POST["username"] ?? '');
        $password = trim($_POST["password"] ?? '');
        $confirm_password = trim($_POST["confirm_password"] ?? '');
        $posted_referrer = $_POST["return_to"] ?? $referrer;
        $user_captcha = trim($_POST["captcha_selected"] ?? '');

    // Re-validate posted referrer
    if (!preg_match('~^https?://~i', $posted_referrer)) {
        $posted_referrer = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $posted_referrer;
    }

    if (!filter_var($posted_referrer, FILTER_VALIDATE_URL)) {
        $posted_referrer = 'index.php';
    } elseif (strpos($posted_referrer, $_SERVER['HTTP_HOST']) === false) {
        $posted_referrer = 'index.php';
    }

    // Validation
    if ($username === "") $errors[] = "Username is required.";
    if (strlen($username) < 3) $errors[] = "Username must be at least 3 characters.";
    if (strlen($username) > 50) $errors[] = "Username must be less than 50 characters.";
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) $errors[] = "Username can only contain letters, numbers, and underscores.";
    
    if ($email === "") $errors[] = "Email is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Please enter a valid email address.";
    
    if ($password === "") $errors[] = "Password is required.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    
    if ($confirm_password !== $password) $errors[] = "Passwords do not match.";

    // Captcha validation
    if ($user_captcha !== $_SESSION['captcha_expected']) {
        $captcha_errors[] = "Please select the correct " . ucfirst($_SESSION['captcha_expected']) . ".";
    }

    // Check if username or email exists
    if (empty($errors) && empty($captcha_errors)) {
        $stmt = $conn->prepare("SELECT username, email FROM users WHERE username = ? OR email = ? LIMIT 2");
        if (!$stmt) {
            $errors[] = "Database error. Please try again.";
            error_log('Register username check failed: ' . $conn->error);
        } else {
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                if ($row['username'] === $username) {
                    $errors[] = "Username already exists. Please choose another.";
                }
                if ($row['email'] === $email) {
                    $errors[] = "Email already exists. Please use a different email.";
                }
            }
            $stmt->close();
        }
    }

    // Create account if no errors
    if (empty($errors) && empty($captcha_errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
        if (!$stmt) {
            $errors[] = "Database error. Please try again.";
            error_log('Register insert failed: ' . $conn->error);
        } else {
            $stmt->bind_param("sss", $email, $username, $hashed_password);
            
            if ($stmt->execute()) {
                $user_id = $conn->insert_id;
                $_SESSION["user_id"] = (int)$user_id;
                $_SESSION["username"] = $username;
                
                // Clear captcha
                unset($_SESSION['captcha_expected']);
                
                header("Location: " . htmlspecialchars($posted_referrer, ENT_QUOTES, 'UTF-8'));
                exit;
            } else {
                $errors[] = "Registration failed. Please try again.";
                error_log('Register execute failed: ' . $stmt->error);
            }
            $stmt->close();
        }
    }
    } // Close CSRF validation block
}

$twig = require __DIR__ . '/twig_init.php';
$twig->display('register.twig', [
    'errors' => $errors,
    'captcha_errors' => $captcha_errors,
    'emojis' => $emojis,
    'keys' => $keys,
    'prompt_key' => $prompt_key,
    'username' => isset($_SESSION['username']) ? $_SESSION['username'] : '',
    'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
    'return_to' => $referrer,
    'csrf_token' => $_SESSION['csrf_token'],
    // For form value preservation
    'form_email' => isset($email) ? $email : '',
    'form_username' => isset($username) ? $username : '',
    'selected_captcha' => isset($_POST['captcha_selected']) ? $_POST['captcha_selected'] : ''
]);
?>