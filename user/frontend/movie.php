<?php 
$apiKey = "25882367"; 

if (!isset($_GET["imdbID"])) {
    die("<h2 style='color:white; padding:20px;'>No movie ID provided.</h2>");
}

$imdbID = htmlspecialchars($_GET["imdbID"]);
$url = "https://www.omdbapi.com/?apikey={$apiKey}&i={$imdbID}&plot=full";

$response = file_get_contents($url);

if (!$response) {
    die("<h2 style='color:white; padding:20px;'>Could not reach OMDb API.</h2>");
}

$movie = json_decode($response, true);

if (!$movie || $movie["Response"] === "False") {
    $error = $movie["Error"] ?? "Unknown OMDb Error";
    die("<h2 style='color:white; padding:20px;'>OMDb Error: $error</h2>");
}

// Fallback in case poster is missing
$mainPoster = ($movie["Poster"] !== "N/A")
    ? $movie["Poster"]
    : "/RATEFLIXWEB/images/placeholder.jpg";
?>
<!DOCTYPE html>
<html lang="en">

<?php include "./components/head.php"; ?>

<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col">

<?php include "./components/header.php"; ?>

<main class="pt-36 pb-20 flex-1">
    <div class="max-w-6xl mx-auto px-4">

        <!-- Back btn -->
        <a href="/RATEFLIXWEB/user/frontend/index.php" 
            class="inline-block mb-6 text-secondary hover:text-accent text-lg">
            ← Back to Home
        </a>

        <!-- MOVIE detaisl -->
        <div class="bg-primaryLight/20 p-8 rounded-xl shadow-lg border border-primaryLight w-full overflow-hidden">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 items-start">

                <img src="<?= $mainPoster ?>" 
                    alt="<?= $movie["Title"] ?>"
                    class="rounded-lg shadow-lg w-full object-cover"/>

                <div class="md:col-span-2 space-y-4">

                    <h1 class="text-4xl font-extrabold text-white mb-4">
                        <?= $movie["Title"] ?>
                    </h1>

                    <div class="space-y-2 text-gray-200">
                        <p><strong>Year:</strong> <?= $movie["Year"] ?></p>
                        <p><strong>Runtime:</strong> <?= $movie["Runtime"] ?></p>
                        <p><strong>Genre:</strong> <?= $movie["Genre"] ?></p>
                        <p><strong>Director:</strong> <?= $movie["Director"] ?></p>
                        <p class="flex items-center gap-2">
                            <strong>IMDB Rating:</strong> 
                            <span class="text-yellow-400">⭐ <?= $movie["imdbRating"] ?>/10</span>
                        </p>
                    </div>

                    <p class="text-gray-300 leading-relaxed text-lg">
                        <?= $movie["Plot"] ?>
                    </p>

                    <a href="#reviews"
                        class="inline-block bg-accent px-5 py-3 rounded-lg text-white font-semibold hover:bg-accent/80 shadow">
                        Leave Review
                    </a>

                </div>
            </div>
        </div>
        
<!-- review -->
<div class="mt-10 bg-slate-800/40 p-6 rounded-xl border border-slate-700">
    <h3 class="text-2xl font-bold mb-4">Rate This Movie</h3>
<div class="main-container mb-10">
    <div class="rating-box">

        <input type="radio" name="rating" id="rating-5" value="5">
        <label for="rating-5" class="fa-solid fa-star"></label>

        <input type="radio" name="rating" id="rating-4" value="4">
        <label for="rating-4" class="fa-solid fa-star"></label>

        <input type="radio" name="rating" id="rating-3" value="3">
        <label for="rating-3" class="fa-solid fa-star"></label>

        <input type="radio" name="rating" id="rating-2" value="2">
        <label for="rating-2" class="fa-solid fa-star"></label>

        <input type="radio" name="rating" id="rating-1" value="1">
        <label for="rating-1" class="fa-solid fa-star"></label>

        <div class="emojis"></div>
    </div>
</div>

    </div>

    <textarea 
        placeholder="Write your review..." 
        class="w-full mt-6 p-4 rounded-lg bg-slate-900 text-white border border-slate-700"
        rows="4"
    ></textarea>

    <button class="mt-4 bg-accent px-6 py-3 rounded-lg text-white font-bold hover:bg-accent/80">
        Submit Review
    </button>
</div>

    </div>
</main>

<?php include "./components/footer.php"; ?>

</body>
</html>
