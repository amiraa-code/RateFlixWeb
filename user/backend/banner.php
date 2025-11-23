<?php
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

require './include/db.php';

if ($_SERVER['REQUEST_METHOD'] === "GET"){
    //fetch all banners
    $stmt = "SELECT * FROM banner;";
    if ($result = $conn->query($stmt)) {
        //populating array with rows that hav been returned
        $arr = array();
        while ($row = $result->fetch_assoc()) {
            $arr[] = $row;
        }

        http_response_code(200);
        echo json_encode(['banner' => $arr]);
    } else {
        //send error message
        http_response_code(500);
        echo json_encode(['error' => 'Error fetching banners']);
        error_log('Banner query failed: ' . $conn->error);
    }
    exit();
}