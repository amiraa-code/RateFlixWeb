<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once './include/db.php';

function jsonError($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(['status' => 'error', 'message' => $msg]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    jsonError("You must be logged in.", 401);
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError("Invalid request.", 405);
}

$reviewId = isset($_POST['review_id']) ? intval($_POST['review_id']) : 0;
if ($reviewId <= 0) jsonError("Invalid review ID.");

// Make sure the review belongs to the user
$stmt = $conn->prepare("SELECT user_id FROM reviews WHERE review_id = ?");
$stmt->bind_param("i", $reviewId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    jsonError("Review not found.");
}

$stmt->bind_result($ownerId);
$stmt->fetch();
$stmt->close();

if ($ownerId !== $userId) {
    jsonError("You cannot delete another user's review.", 403);
}

// DELETE - hide from display
$stmt = $conn->prepare("UPDATE reviews SET is_hidden = 1 WHERE review_id = ?");
$stmt->bind_param("i", $reviewId);
$stmt->execute();
$stmt->close();

echo json_encode([
    'status' => 'ok',
    'message' => 'Review deleted (soft delete).'
]);
