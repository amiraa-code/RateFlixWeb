<?php 
header('Content-Type: application/json; charset=utf-8');
require './include/db.php';

if (!isset($_GET['category'])) {
    echo json_encode(['status' => 'error', 'message' => 'No category provided']);
    exit;
}

$category = $_GET['category'];

// get category_id
$stmt = $conn->prepare("SELECT category_id FROM categories WHERE category_name = ?");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Category not found']);
    exit;
}

$category_id = $result['category_id'];

// get movies from DB
$sql = $conn->prepare("
SELECT 
    imdbID,
    title,
    poster,
    release_date
FROM movies
WHERE category_id = ?
");
$sql->bind_param("i", $category_id);
$sql->execute();
$movies = $sql->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    'status' => 'ok',
    'movies' => $movies
]);
exit;
