<?php
require "./include/db.php";
require './include/db.php';

echo "<h2>Connected To: " . $conn->host_info . "</h2>";

$result = $conn->query("SELECT DATABASE() AS db");
$row = $result->fetch_assoc();
echo "<h3>Using Database: " . $row['db'] . "</h3>";


$apiKey = "25882367";

$movies = [
    "Action" => [
        "tt4154756",
        "tt4912910",
        "tt4154796",
        "tt6146586",
        "tt8936646",
        "tt6723592",
        "tt9376612",
        "tt2382320",
        "tt1745960",
        "tt1877830",
        "tt9603212",
        "tt6791350"
    ],
    "Comedy" => [
        "tt3104988",
        "tt2704998",
        "tt2584384",
        "tt8946378",
        "tt9484998",
        "tt10161886",
        "tt6264654",
        "tt11286314",
        "tt6710474",
        "tt11564570",
        "tt1517268",
        "tt17527468"
    ],
    "Horror" => [
        "tt6644200",
        "tt7784604",
        "tt6857112",
        "tt8772262",
        "tt1051906",
        "tt8508734",
        "tt8332922",
        "tt3811906",
        "tt15474916",
        "tt13560574",
        "tt10638522",
        "tt8760708"
    ],
    "Romance" => [
        "tt3846674",
        "tt5164432",
        "tt7653254",
        "tt8637428",
        "tt9683478",
        "tt9214832",
        "tt12889404",
        "tt10370710",
        "tt13320622",
        "tt15218000",
        "tt13238346",
        "tt15789038"
    ]
];

echo "<pre>";

foreach ($movies as $categoryName => $imdbList) {

    // ✅ find category_id from DB
    $catStmt = $conn->prepare("SELECT category_id FROM categories WHERE category_name = ?");
    $catStmt->bind_param("s", $categoryName);
    $catStmt->execute();
    $catStmt->bind_result($category_id);
    $catStmt->fetch();
    $catStmt->close();

    if (!$category_id) {
        echo "❌ ERROR — Category missing in DB: $categoryName\n";
        continue;
    }

    echo "\n=== ✅ Seeding $categoryName (category_id $category_id) ===\n";

    foreach ($imdbList as $imdbID) {

        // ✅ skip duplicates
        $check = $conn->prepare("SELECT movie_id FROM movies WHERE imdbID = ?");
        $check->bind_param("s", $imdbID);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo "⚠️ Already exists: $imdbID\n";
            $check->close();
            continue;
        }
        $check->close();

        // ✅ fetch data
        $url = "https://www.omdbapi.com/?apikey=$apiKey&i=$imdbID&plot=full";
        $data = json_decode(file_get_contents($url), true);

        if (!$data || $data["Response"] === "False") {
            echo "❌ FAILED: $imdbID (" . ($data["Error"] ?? "Unknown") . ")\n";
            continue;
        }

        $title       = $data["Title"];
        $description = $data["Plot"];
        $poster      = ($data["Poster"] !== "N/A") ? $data["Poster"] : null;
        $rating      = $data["imdbRating"];
        $releaseDate = $data["Year"];

        // ✅ insert
        $insert = $conn->prepare("
            INSERT INTO movies (imdbID, category_id, title, description, poster, rating, release_date)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $insert->bind_param(
            "sisssss",
            $imdbID,
            $category_id,
            $title,
            $description,
            $poster,
            $rating,
            $releaseDate
        );

        if ($insert->execute()) {
            echo "✅ Inserted: $title ($imdbID)\n";
        } else {
            echo "❌ Failed inserting $imdbID: {$insert->error}\n";
        }

        $insert->close();
    }
}

echo "\n🎉 DONE — Movies seeded successfully!";
