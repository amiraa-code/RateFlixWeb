<?php
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

require './include/db.php';

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
   http_response_code(405);
   echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
   exit();
}

$cache_file = __DIR__ . '/../../cache/categories.json';
$cache_time = 600; // 10 minutes
if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_time)) {
   readfile($cache_file);
   exit();
}

try {
   // Use prepared statement to prevent SQL injection
   $stmt = $conn->prepare("SELECT category_name FROM categories ORDER BY category_name ASC");
   
   if (!$stmt) {
      throw new Exception('Database prepare failed');
   }
   
   if (!$stmt->execute()) {
      throw new Exception('Query execution failed');
   }
   
   $result = $stmt->get_result();
   $categories = [];
   
   while ($row = $result->fetch_assoc()) {
      $categories[] = htmlspecialchars($row['category_name'], ENT_QUOTES, 'UTF-8');
   }
   
   $stmt->close();
   
   http_response_code(200);
   $response = [
      'status' => 'ok',
      'categories' => $categories,
      'count' => count($categories)
   ];
   file_put_contents($cache_file, json_encode($response));
   echo json_encode($response);
   exit();
   
} catch (Exception $e) {
   http_response_code(500);
   // Don't expose internal error details to client
   echo json_encode(['status' => 'error', 'message' => 'Failed to fetch categories']);
   error_log('Categories fetch error: ' . $e->getMessage());
}

exit();