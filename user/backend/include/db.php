<?php
// Database connection with error handling
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "rateflix";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_errno) {
    error_log("DB Connection failed: " . $conn->connect_error);
    die("Database connection error.");
}
