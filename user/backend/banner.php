<?php
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

require './include/db.php';

$cache_file = __DIR__ . '/../../cache/banners.json';
$cache_time = 600; // 10 minutes

if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_time)) {
    readfile($cache_file);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === "GET"){
    //fetch all banners
    $stmt = "SELECT * FROM banner;";
    if ($result = $conn->query($stmt)) {
        //populating array with rows that hav been returned
        $arr = array();
        while ($row = $result->fetch_assoc()) {
            $arr[] = $row;
        }
        $response = ['banner' => $arr];
        file_put_contents($cache_file, json_encode($response));
        http_response_code(200);
        echo json_encode($response);
    } else {
        //send error message
        http_response_code(500);
        echo json_encode(['error' => 'Error fetching banners']);
        error_log('Banner query failed: ' . $conn->error);
    }
    exit();
}