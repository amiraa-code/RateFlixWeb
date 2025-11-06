<?php
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

    echo json_encode(['banner' => $arr]);
   } else {
    //send error message
    echo json_encode(['error' => 'Ooopsssyy Spmething went wrong!']);
   }
   exit();
}