<?php
session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
require "../backend/include/db.php";

if (!isset($_GET["imdbID"])) {
    die("<h2 style='color:white; padding:20px;'>No movie ID provided.</h2>");
}

$imdbID = trim($_GET["imdbID"]);

// Validate imdbID format
if (!preg_match('/^tt\d{7,8}$/', $imdbID)) {
    die("<h2 style='color:white; padding:20px;'>Invalid movie ID format.</h2>");
}

$stmt = $conn->prepare("
    SELECT title, description, poster, rating, release_date 
    FROM movies 
    WHERE imdbID = ?
");

if (!$stmt) {
    die("<h2 style='color:white; padding:20px;'>Database error.</h2>");
}

$stmt->bind_param("s", $imdbID);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$movie) {
    die("<h2 style='color:white; padding:20px;'>Movie not found in database.</h2>");
}

$mainPoster = !empty($movie["poster"]) 
    ? $movie["poster"] 
    : "/RATEFLIXWEB/images/placeholder.jpg";

$movie['poster'] = $mainPoster;

$twig = require __DIR__ . '/twig_init.php';
$twig->display('movie.twig', [
    'movie' => $movie,
    'imdbID' => $imdbID,
    'username' => isset($_SESSION['username']) ? $_SESSION['username'] : '',
    'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
    'is_logged_in' => isset($_SESSION['user_id']),
    'return_to' => "/RATEFLIXWEB/user/frontend/movie.php?imdbID=" . urlencode($imdbID)
]);
exit;
