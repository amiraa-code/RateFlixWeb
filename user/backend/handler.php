<?php
require './include/db.php';

if ($_SERVER['REQUEST_METHOD'] === "GET"){
   $stmt = "SELECT category_name FROM categories WHERE category_id = 3;";
   if ($result = $conn->query($stmt)) {
    //populating array with rows that hav been returned
    $arr = array();
    while ($row = $result->fetch_assoc()) {
    $arr[] = $row['category_name'];
}

    echo json_encode(['category' => $arr]);
   } else {
    //send error message
    echo json_encode(['error' => 'Ooopsssyy Spmething went wrong!']);
   }
   exit();
}

