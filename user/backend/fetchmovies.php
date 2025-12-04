<?php 
header('Content-Type: application/json; charset=utf-8');
require './include/db.php';

if (!isset($_GET['category'])) {
    echo json_encode(['status' => 'error', 'message' => 'No category provided']);
    exit;
}

$category = $_GET['category'];
$cache_file = __DIR__ . '/../../cache/movies_' . preg_replace('/[^a-zA-Z0-9_]/', '', $category) . '.json';
$cache_time = 600; // 10 minutes

if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_time)) {
    // Serve from cache
    readfile($cache_file);
    exit;
}

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

$response = [
    'status' => 'ok',
    'movies' => $movies
];
file_put_contents($cache_file, json_encode($response));
echo json_encode($response);
exit;
