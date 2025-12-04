<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
require_once './include/db.php';

function jsonError($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(['status' => 'error', 'message' => $msg]);
    exit;
}

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    jsonError("You must be logged in to edit a review.", 401);
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError("Invalid request method.", 405);
}

$reviewId   = isset($_POST['review_id']) ? intval($_POST['review_id']) : 0;
$rating     = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$reviewText = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';

if ($reviewId <= 0) jsonError("Invalid review ID.");
if ($rating < 1 || $rating > 5) jsonError("Rating must be between 1–5.");
if (strlen($reviewText) < 2) jsonError("Review too short.");
if (strlen($reviewText) > 5000) jsonError("Review too long. Maximum 5000 characters.");

// Check ownership
$stmt = $conn->prepare("SELECT user_id FROM reviews WHERE review_id = ? AND is_hidden = 0");
$stmt->bind_param("i", $reviewId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    jsonError("Review not found.");
}

$stmt->bind_result($reviewOwner);
$stmt->fetch();
$stmt->close();

if ($reviewOwner !== $userId) {
    jsonError("You cannot edit someone else's review.", 403);
}

// Update review
$stmt = $conn->prepare("
    UPDATE reviews
    SET rating = ?, review_text = ?, updated_at = NOW()
    WHERE review_id = ?
");

$stmt->bind_param("isi", $rating, $reviewText, $reviewId);

if (!$stmt->execute()) {
    error_log('Review update failed for user_id: ' . $userId . ', review_id: ' . $reviewId);
    jsonError("Failed to update review.", 500);
}

$stmt->close();

echo json_encode([
    'status' => 'ok',
    'message' => 'Review updated successfully.'
]);
