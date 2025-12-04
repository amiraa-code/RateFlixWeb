<?php
header("Content-Type: application/json; charset=utf-8");
require "./include/db.php";

$title = trim($_GET['title'] ?? '');
$category = trim($_GET['category'] ?? '');
$year = trim($_GET['year'] ?? '');

$where = [];
$params = [];
$types = '';

if ($title !== '') {
    $where[] = 'm.title LIKE ?';
    $params[] = "%$title%";
    $types .= 's';
}
if ($category !== '') {
    $where[] = 'c.category_name = ?';
    $params[] = $category;
    $types .= 's';
}
if ($year !== '') {
    $where[] = 'm.release_date = ?';
    $params[] = $year;
    $types .= 's';
}

$sql = "SELECT 
            m.imdbID,
            m.title,
            m.poster,
            m.release_date,
            c.category_name
        FROM movies m
        JOIN categories c ON m.category_id = c.category_id";
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY m.title ASC LIMIT 50';

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Prepare failed']);
    exit;
}
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$movies = [];
while ($row = $result->fetch_assoc()) {
    $movies[] = $row;
}
$stmt->close();

http_response_code(200);
echo json_encode(['status' => 'ok', 'movies' => $movies]);
exit;
