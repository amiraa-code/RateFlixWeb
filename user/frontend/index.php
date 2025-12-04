<?php
session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

$twig = require __DIR__ . '/twig_init.php';
$twig->display('index.twig', [
  'username' => isset($_SESSION['username']) ? $_SESSION['username'] : '',
  'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
  'return_to' => '/RATEFLIXWEB/user/frontend/index.php'
]);
?>
