<?php
// Database connectiion
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "rateflix";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_errno) {
    error_log("DB Connection failed: " . $conn->connect_error);
    die("Database connection error.");
}
