<?php
session_start();
require_once "../backend/include/db.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

$username = trim($_POST["username"]);
$email = trim($_POST["email"]);
$password = trim($_POST["password"]);
$confirm = trim($_POST["confirm"]);

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
        $_SESSION["user_id"] = $stmt->insert_id;
        $_SESSION["username"] = $username;
        header("Location: index.php");
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

    <div>
    <label class="block mb-1 text-sm font-medium">Username</label>
    <input 
        type="text" 
        name="username"
        class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700 focus:outline-none focus:border-accent"
    >
    </div>

    <div>
    <label class="block mb-1 text-sm font-medium">Email</label>
    <input 
        type="email" 
        name="email"
        class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700 focus:outline-none focus:border-accent"
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

    <div>
    <label class="block mb-1 text-sm font-medium">Confirm Password</label>
    <input 
        type="password" 
        name="confirm"
        class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700 focus:outline-none focus:border-accent"
    >
    </div>

    <button 
    type="submit"
    class="w-full mt-2 bg-accent py-3 rounded-lg font-bold hover:bg-accent/80 transition">
    Register
    </button>
</form>

<p class="mt-4 text-sm text-gray-400 text-center">
    Already have an account?
    <a href="login.php" class="text-accent hover:underline">Login</a>
</p>

</div>
</main>
<?php include "./components/footer.php"; ?>
</body>
</html>
