<?php
session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
require_once "../backend/include/db.php"; 

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = trim($_POST["password"] ?? '');

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

                    header("Location: index.php");
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
?>
<!DOCTYPE html>
<html lang="en">

<?php include "./components/head.php"; ?>

<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col">
<?php include "./components/header.php"; ?>

<main class="flex-1 pt-28 flex items-center justify-center px-4">
<div class="bg-slate-800 p-8 rounded-xl w-full max-w-md shadow-lg border border-slate-700">

<h1 class="text-3xl font-bold mb-6 text-center">Login</h1>

<?php if (!empty($errors)): ?>
    <div class="mb-4 bg-red-500/20 border border-red-400 text-red-200 text-sm p-3 rounded">
    <?php foreach ($errors as $e): ?>
        <p><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" class="space-y-4">

    <div>
    <label class="block mb-1 text-sm font-medium">Username</label>
    <input 
        type="text" 
        name="username" 
        class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700 focus:outline-none focus:border-accent"
        value="<?= isset($username) ? htmlspecialchars($username) : '' ?>"
    >
    </div>

    <div>
    <label class="block mb-1 text-sm font-medium">Password</label>
    <input 
        type="password" 
        name="password" 
        class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700 focus:outline-none focus:border-accent"
    >
    </div>

    <button 
    type="submit"
    class="w-full mt-2 bg-accent py-3 rounded-lg font-bold hover:bg-accent/80 transition">
    Login
    </button>
</form>

<p class="mt-4 text-sm text-gray-400 text-center">
    Don't have an account?
    <a href="register.php" class="text-accent hover:underline">Create one</a>
</p>

</div>
</main>

<?php include "./components/footer.php"; ?>
</body>
</html>
