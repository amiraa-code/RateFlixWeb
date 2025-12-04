<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once './include/db.php';

function jsonError($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(['status' => 'error', 'message' => $msg]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Invalid request method.', 405);
}

// must be logged in
if (!isset($_SESSION['user_id'])) {
    jsonError('You must be logged in to add a review.', 401);
}

$userId = $_SESSION['user_id'];

// get & validate input
$imdbID = isset($_POST['imdbID']) ? trim($_POST['imdbID']) : '';
$rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
$reviewText = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';

if ($imdbID === '' || strlen($imdbID) > 20) {
    jsonError('Invalid movie ID.');
}
if ($rating < 1 || $rating > 5) {
    jsonError('Rating must be between 1 and 5.');
}
if (strlen($reviewText) < 2) {
    jsonError('Please write a longer review.');
}

$stmt = $conn->prepare("
    INSERT INTO reviews (user_id, imdbID, rating, review_text, is_hidden)
    VALUES (?, ?, ?, ?, 0)
");

if (!$stmt) {
    jsonError('Database error: ' . $conn->error, 500);
}

$stmt->bind_param("isis", $userId, $imdbID, $rating, $reviewText);

if (!$stmt->execute()) {
    jsonError('Failed to save review.', 500);
}

$newId = $stmt->insert_id;
$stmt->close();

// return review info
echo json_encode([
    'status' => 'ok',
    'message' => 'Review saved!',
    'review' => [
        'review_id' => $newId,
        'user_id'   => $userId,
        'imdbID'    => $imdbID,
        'rating'    => $rating,
        'review_text' => htmlspecialchars($reviewText, ENT_QUOTES, 'UTF-8'),
        'username'  => isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : 'You',
        'created_at' => date('Y-m-d H:i:s'),
    ]
]);
