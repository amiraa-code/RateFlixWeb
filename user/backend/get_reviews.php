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

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    jsonError('Invalid request method.', 405);
}

// Validate imdbID parameter
if (!isset($_GET['imdbID'])) {
    jsonError('No movie ID provided.');
}

$imdbID = trim($_GET['imdbID']);

// Validate imdbID format (IMDb IDs are typically tt followed by digits)
if (!preg_match('/^tt\d{7,8}$/', $imdbID)) {
    jsonError('Invalid movie ID format.');
}

try {
    $stmt = $conn->prepare("
        SELECT r.review_id, r.user_id, r.rating, r.review_text, r.created_at,
               u.username
        FROM reviews r
        JOIN users u ON r.user_id = u.user_id
        WHERE r.imdbID = ? AND r.is_hidden = 0
        ORDER BY r.created_at DESC
        LIMIT 1000
    ");

    if (!$stmt) {
        throw new Exception('Database prepare failed');
    }

    $stmt->bind_param("s", $imdbID);
    
    if (!$stmt->execute()) {
        throw new Exception('Query execution failed');
    }
    
    $result = $stmt->get_result();

    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        // Validate rating is between 1-5
        $rating = (int)$row['rating'];
        if ($rating < 1 || $rating > 5) {
            continue; // Skip invalid ratings
        }

        $reviews[] = [
            'review_id'   => (int)$row['review_id'],
            'user_id'     => (int)$row['user_id'],
            'rating'      => $rating,
            'review_text' => htmlspecialchars($row['review_text'], ENT_QUOTES, 'UTF-8'),
            'created_at'  => $row['created_at'],
            'username'    => htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'),
        ];
    }

    $stmt->close();

    http_response_code(200);
    echo json_encode([
        'status' => 'ok',
        'reviews' => $reviews,
        'count' => count($reviews)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    error_log('Get reviews error: ' . $e->getMessage());
    jsonError('Failed to retrieve reviews.');
}
