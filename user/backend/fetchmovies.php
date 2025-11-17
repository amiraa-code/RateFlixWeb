<?php
header('Content-Type: application/json; charset=utf-8');

require './include/db.php';

$omdbApiKey = '25882367';
function jsonError($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(['error' => $msg]);
    exit;
}

if (empty($omdbApiKey)) {
    jsonError('OMDb API key not configured.');
}
if (!isset($conn) || !$conn) {
    jsonError('Database connection failed.');
}

// functionaluty
if (!isset($_GET['category'])) {
    jsonError('No category provided.');
}

$category = urlencode($_GET['category']);
$url = "https://www.omdbapi.com/?apikey=$omdbApiKey&s=$category&type=movie";

$response = file_get_contents($url);
if (!$response) {
    jsonError('Failed to reach OMDb API.');
}

$data = json_decode($response, true);

if (isset($data['Response']) && $data['Response'] === 'True') {
    echo json_encode(['status' => 'ok', 'movies' => $data['Search']]);
} else {
    jsonError('No movies found for this category.');
}
