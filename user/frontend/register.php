<?php
session_start();
require_once "../backend/include/db.php";

$errors = [];
$return_to = $_GET['return_to'] ?? $_SERVER['HTTP_REFERER'] ?? 'index.php';

// Sanitize return_to to prevent open redirect
if (!preg_match('~^https?://~i', $return_to)) {
    $return_to = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $return_to;
}

if (!filter_var($return_to, FILTER_VALIDATE_URL)) {
    $return_to = 'index.php';
} elseif (strpos($return_to, $_SERVER['HTTP_HOST']) === false) {
    $return_to = 'index.php';
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

$username = trim($_POST["username"]);
$email = trim($_POST["email"]);
$password = trim($_POST["password"]);
$confirm = trim($_POST["confirm"]);
$posted_return_to = $_POST["return_to"] ?? $return_to;




$captcha_selected = $_POST['captcha_selected'] ?? null;
$captcha_expected = $_SESSION['captcha_expected'] ?? null;
if ($captcha_selected === null || $captcha_selected === '') {
    $errors[] = "Please select the captcha.";
} elseif ($captcha_selected !== $captcha_expected) {
    $errors[] = "Captcha selection is incorrect.";
}


// Re-validate posted return_to
if (!preg_match('~^https?://~i', $posted_return_to)) {
    $posted_return_to = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $posted_return_to;
}

if (!filter_var($posted_return_to, FILTER_VALIDATE_URL)) {
    $posted_return_to = 'index.php';
} elseif (strpos($posted_return_to, $_SERVER['HTTP_HOST']) === false) {
    $posted_return_to = 'index.php';
}

if ($username === "") $errors[] = "Username is required.";
if ($email === "") $errors[] = "Email is required.";
if ($password === "") $errors[] = "Password is required.";
if ($password !== $confirm) $errors[] = "Passwords do not match.";

// Check unique username
if (empty($errors)) {
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $errors[] = "Username already taken.";
    }
    $stmt->close();
}

// Check unique email
if (empty($errors)) {
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $errors[] = "Email already exists.";
    }
    $stmt->close();
}

if (empty($errors)) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT INTO users (username, email, password)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("sss", $username, $email, $hashed);

    if ($stmt->execute()) {
        // Log the user in after successful registration
        $_SESSION["user_id"] = $stmt->insert_id;
        $_SESSION["username"] = $username;
        // Always redirect to index.php after registration
        header("Location: /RATEFLIXWEB/user/frontend/index.php");
        exit;
    } else {
        $errors[] = "Something went wrong. Try again.";
    }

    $stmt->close();
}
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include "./components/head.php"; ?>

<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col">
<?php include "./components/header.php"; ?>

<main class="flex-1 pt-32 flex items-center justify-center px-4">
<div class="bg-slate-800 p-8 rounded-xl w-full max-w-md shadow-lg border border-slate-700">

<h1 class="text-3xl font-bold mb-6 text-center">Create an Account</h1>

<?php if (!empty($errors)): ?>
    <div class="mb-4 bg-red-500/20 border border-red-400 text-red-200 text-sm p-3 rounded">
    <?php foreach ($errors as $e): ?>
        <p><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" class="space-y-4">
    <input type="hidden" name="return_to" value="<?= htmlspecialchars($return_to, ENT_QUOTES, 'UTF-8') ?>">
    <div>
    <label class="block mb-1 text-sm font-medium">Username</label>
    <input 
        type="text" 
        name="username"
        value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700 focus:outline-none focus:border-accent"
    >
    </div>

    <div>
    <label class="block mb-1 text-sm font-medium">Email</label>
    <input 
        type="email" 
        name="email"
        value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700 focus:outline-none focus:border-accent"
    >
    </div>

    <div>
    <label class="block mb-1 text-sm font-medium">Password</label>
    <input 
        type="password" 
        name="password"
        value="<?= htmlspecialchars($_POST['password'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700 focus:outline-none focus:border-accent"
    >
    </div>

    <div>
    <label class="block mb-1 text-sm font-medium">Confirm Password</label>
    <input 
        type="password" 
        name="confirm"
        value="<?= htmlspecialchars($_POST['confirm'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700 focus:outline-none focus:border-accent"
    >
    </div>

    <?php
    // Emoji captcha 
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
    ?>
    <div class="mb-4 flex flex-col items-center">
        <label class="block text-sm font-medium text-gray-300 mb-2">Captcha: Click the <span class="font-bold text-accent"><?php echo ucfirst($prompt_key); ?></span></label>
        <div class="flex gap-4 justify-center mb-2">
            <?php foreach ($keys as $k): ?>
                <label style="cursor:pointer; font-size:2.5rem;">
                    <input type="radio" name="captcha_selected" value="<?php echo $k; ?>" style="display:none;" <?= (isset($_POST['captcha_selected']) && $_POST['captcha_selected'] === $k) ? 'checked' : '' ?> >
                    <span class="inline-block w-16 h-16 rounded border-2 border-slate-700 hover:border-accent transition flex items-center justify-center text-4xl bg-slate-900"><?php echo $emojis[$k]; ?></span>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <button 
    type="submit"
    class="w-full mt-2 bg-accent py-3 rounded-lg font-bold hover:bg-accent/80 transition">
    Register
    </button>
</form>

<p class="mt-4 text-sm text-gray-400 text-center">
    Already have an account?
    <a href="login.php?return_to=<?= urlencode($return_to) ?>" class="text-accent hover:underline">Login</a>
</p>

</div>
</main>
<?php include "./components/footer.php"; ?>
</body>
</html>
