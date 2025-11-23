<?php
session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
require "../backend/include/db.php";

if (!isset($_GET["imdbID"])) {
    die("<h2 style='color:white; padding:20px;'>No movie ID provided.</h2>");
}

$imdbID = trim($_GET["imdbID"]);

// Validate imdbID format
if (!preg_match('/^tt\d{7,8}$/', $imdbID)) {
    die("<h2 style='color:white; padding:20px;'>Invalid movie ID format.</h2>");
}

$stmt = $conn->prepare("
    SELECT title, description, poster, rating, release_date 
    FROM movies 
    WHERE imdbID = ?
");

if (!$stmt) {
    die("<h2 style='color:white; padding:20px;'>Database error.</h2>");
}

$stmt->bind_param("s", $imdbID);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$movie) {
    die("<h2 style='color:white; padding:20px;'>Movie not found in database.</h2>");
}

$mainPoster = !empty($movie["poster"])
    ? $movie["poster"]
    : "/RATEFLIXWEB/images/placeholder.jpg";
?>
<!DOCTYPE html>
<html lang="en">

<?php include "./components/head.php"; ?>

<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col">

<?php include "./components/header.php"; ?>

<main class="pt-10 pb-20 flex-1">
  <div class="max-w-6xl mx-auto px-4">

    <a href="/RATEFLIXWEB/user/frontend/index.php" 
       class="inline-block mb-6 text-secondary hover:text-accent text-lg">
        ← Back to Home
    </a>

    <div class="bg-primaryLight/20 p-8 rounded-xl shadow-lg border border-primaryLight w-full overflow-hidden">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-10 items-start">

        <img src="<?= htmlspecialchars($mainPoster) ?>" 
             alt="<?= htmlspecialchars($movie["title"]) ?>"
             class="rounded-lg shadow-lg w-full object-cover"/>

        <div class="md:col-span-2 space-y-4">

          <h1 class="text-4xl font-extrabold text-white mb-4">
            <?= htmlspecialchars($movie["title"]) ?>
          </h1>

          <div class="space-y-2 text-gray-200">
            <p><strong>Year:</strong> <?= htmlspecialchars($movie["release_date"]) ?></p>

            <p class="flex items-center gap-2">
              <strong>IMDB Rating:</strong> 
              <span class="text-yellow-400">
                <?= htmlspecialchars($movie["rating"] ?? "N/A") ?>/10
              </span>
            </p>
          </div>

          <p class="text-gray-300 leading-relaxed text-lg">
            <?= htmlspecialchars($movie["description"]) ?>
          </p>

          <button id="openModal" 
                  class="inline-block bg-accent px-5 py-3 rounded-lg text-white font-bold hover:bg-accent/80 shadow">
              Rate This Movie
          </button>
        </div>

      </div>
    </div>

    <!-- Reviews Section -->
    <div id="reviews" class="mt-10 bg-slate-800/40 p-6 rounded-xl border border-slate-700">
      <h3 class="text-2xl font-bold mb-4 text-white">User Reviews</h3>
      <div id="reviewsList" class="space-y-4 text-gray-200">
        <p class="text-gray-400">No reviews yet. Be the first to rate this movie!</p>
      </div>
    </div>

  </div>
</main>

<!-- Rating Modal -->
<div id="ratingModal" 
     class="fixed inset-0 bg-black/70 flex justify-center items-center hidden z-50">
  <div class="bg-slate-800 p-8 rounded-xl w-[90%] max-w-md text-center text-white relative shadow-xl">
    <button id="closeModal" class="absolute top-3 right-3 text-2xl hover:text-accent transition">&times;</button>

    <h3 class="text-lg text-secondary mb-1 font-semibold">RATE THIS MOVIE</h3>
    <h2 class="text-2xl font-bold mb-6">
      <?= htmlspecialchars($movie["title"]) ?>
    </h2>

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

    <textarea id="reviewText"
              placeholder="Write your review..."
              class="w-full mt-2 p-4 rounded-lg bg-slate-900 text-white border border-slate-700 focus:border-accent focus:outline-none transition"
              rows="4"></textarea>

    <button id="submitReviewBtn" 
            class="mt-4 bg-accent px-6 py-3 rounded-lg text-white font-bold hover:bg-accent/80 w-full transition shadow-lg">
      Submit Review
    </button>

  </div>
</div>

<!-- Edit Review Modal -->
<div id="editReviewModal"
     class="fixed inset-0 bg-black/70 flex justify-center items-center hidden z-50">
  <div class="bg-slate-800 p-8 rounded-xl w-[90%] max-w-md text-white relative shadow-xl">
    <button id="closeEditModal" class="absolute top-3 right-3 text-2xl hover:text-accent transition">&times;</button>

    <h2 class="text-xl font-bold mb-4 text-white">Edit Your Review</h2>

    <input type="hidden" id="editReviewId">

    <label class="block mb-2 font-semibold text-white">Rating (1-5)</label>
    <input 
      type="number" 
      id="editRating" 
      min="1" max="5"
      class="w-full p-2 rounded bg-slate-900 text-white border border-slate-700 mb-4 focus:border-accent focus:outline-none transition"
    >

    <label class="block mb-2 font-semibold text-white">Review</label>
    <textarea id="editText" rows="4"
              class="w-full p-3 rounded bg-slate-900 text-white border border-slate-700 focus:border-accent focus:outline-none transition"></textarea>

    <button id="saveEditReview"
            class="mt-4 bg-accent px-6 py-3 rounded-lg text-white font-bold hover:bg-accent/80 w-full transition shadow-lg">
      Save Changes
    </button>
  </div>
</div>

<!-- Login Required Modal -->
<div id="loginRequiredModal"
    class="fixed inset-0 bg-black/70 flex justify-center items-center hidden z-50">
  <div class="modal-box bg-slate-800 p-8 rounded-xl w-[90%] max-w-md text-center text-white shadow-xl">
    <h2 class="text-2xl font-bold mb-4 text-accent">Login Required</h2>
    <p class="text-gray-300 mb-6">You must be logged in to rate this movie.</p>

    <div class="flex gap-4 justify-center">
      <a href="/RATEFLIXWEB/user/frontend/login.php"
         class="bg-accent px-6 py-3 rounded-lg font-bold hover:bg-accent/80 transition shadow-lg">
        Login
      </a>
      <button id="closeLoginRequired"
              class="px-6 py-3 rounded-lg bg-slate-700 hover:bg-slate-600 transition">
        Cancel
      </button>
    </div>
  </div>
</div>

<?php include "./components/footer.php"; ?>

<!-- Logout Modal -->
<div id="logoutModal"
     class="fixed inset-0 bg-black/60 hidden justify-center items-center z-50">
  <div class="bg-slate-800 p-6 rounded-xl w-80 text-center shadow-xl">
    <h2 class="text-xl font-bold mb-4 text-white">Logout?</h2>
    <p class="text-gray-300 mb-6">Are you sure you want to leave?</p>

    <div class="flex gap-4 justify-center">
      <button id="cancelLogout"
              class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-500 transition font-semibold">
        No, Wait!
      </button>
      <a href="/RATEFLIXWEB/user/frontend/logout.php"
         class="px-4 py-2 bg-accent text-white rounded hover:bg-accent/80 transition font-semibold">
        Yes, Done Rating!
      </a>
    </div>
  </div>
</div>

<!-- Variables from PHP -->
<script>
const currentImdbID = "<?= $imdbID ?>";
const isLoggedIn    = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
const loggedUserID  = <?= isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0 ?>;
</script>

<!-- Review + Edit JS -->
<script>
const modal        = document.getElementById("ratingModal");
const openBtn      = document.getElementById("openModal");
const closeBtn     = document.getElementById("closeModal");
const submitBtn    = document.getElementById("submitReviewBtn");

const editModal    = document.getElementById("editReviewModal");
const closeEditBtn = document.getElementById("closeEditModal");
const saveEditBtn  = document.getElementById("saveEditReview");


// Close rating modal
closeBtn.addEventListener("click", () => modal.classList.add("hidden"));

// Close edit modal
closeEditBtn.addEventListener("click", () => editModal.classList.add("hidden"));

// Load Reviews
function loadReviews() {
    fetch(`/RATEFLIXWEB/user/backend/get_reviews.php?imdbID=${encodeURIComponent(currentImdbID)}`)
        .then(res => {
            if (!res.ok) throw new Error('Network response error');
            return res.json();
        })
        .then(data => {
            const list = document.getElementById("reviewsList");
            list.innerHTML = "";

            if (data.status !== "ok") {
                list.innerHTML = `<p class="text-red-400">${data.message || "Error loading reviews."}</p>`;
                return;
            }

            if (!data.reviews || data.reviews.length === 0) {
                list.innerHTML = `<p class="text-gray-400">No reviews yet. Be the first!</p>`;
                return;
            }

            data.reviews.forEach(r => {
                const div = document.createElement("div");
                div.className = "review-card bg-slate-900/60 border border-slate-700 rounded-lg p-4";

                let controls = "";

                if (isLoggedIn && r.user_id == loggedUserID) {
                    controls = `
                        <div class="flex gap-4 text-sm mt-2">
                            <button 
                                class="text-blue-400 hover:text-blue-300 edit-btn" 
                                data-id="${r.review_id}"
                                data-rating="${r.rating}">
                                Edit
                            </button>
                            <button 
                                class="text-red-400 hover:text-red-300 delete-btn" 
                                data-id="${r.review_id}">
                                Delete
                            </button>
                        </div>
                    `;
                }

                div.innerHTML = `
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold">${r.username}</span>
                        <span class="text-yellow-400">⭐ ${r.rating}/5</span>
                    </div>
                    <p class="text-sm mb-1 review-text">${r.review_text}</p>
                    <p class="text-xs text-gray-500">${r.created_at}</p>
                    ${controls}
                `;

                list.appendChild(div);
            });
        })
        .catch(err => {
            console.error(err);
            document.getElementById("reviewsList").innerHTML =
                `<p class="text-red-400">Failed to load reviews.</p>`;
        });
}

// Submit new review
submitBtn.addEventListener("click", () => {
    let ratingInput = document.querySelector("input[name='rating']:checked");
    if (!ratingInput) {
        alert("Please select a star!");
        return;
    }

    const rating  = ratingInput.value;
    const comment = document.getElementById("reviewText").value.trim();

    if (comment.length < 2) {
        alert("Please leave a review.");
        return;
    }

    const body = new URLSearchParams();
    body.append("imdbID", currentImdbID);
    body.append("rating", rating);
    body.append("review_text", comment);

    fetch("/RATEFLIXWEB/user/backend/add_review.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: body.toString()
    })
    .then(res => {
        if (!res.ok) throw new Error('Network response error');
        return res.json();
    })
    .then(data => {
        if (data.status !== "ok") {
            alert(data.message || "Could not save review.");
            return;
        }

        document.getElementById("reviewText").value = "";
        document.querySelectorAll("input[name='rating']").forEach(r => r.checked = false);

        modal.classList.add("hidden");
        loadReviews();
    })
    .catch(err => {
        console.error(err);
        alert("Something went wrong.");
    });
});

// Handle Edit button clicks
document.addEventListener("click", (e) => {
    if (!e.target.classList.contains("edit-btn")) return;

    const btn        = e.target;
    const reviewId   = btn.dataset.id;
    const rating     = btn.dataset.rating;
    const reviewCard = btn.closest(".review-card");
    const textEl     = reviewCard.querySelector(".review-text");
    const text       = textEl ? textEl.textContent.trim() : "";

    document.getElementById("editReviewId").value = reviewId;
    document.getElementById("editRating").value   = rating;
    document.getElementById("editText").value     = text;

    editModal.classList.remove("hidden");
});

// Save edited review
saveEditBtn.addEventListener("click", () => {
    const reviewId = document.getElementById("editReviewId").value;
    const rating   = parseInt(document.getElementById("editRating").value, 10);
    const text     = document.getElementById("editText").value.trim();

    if (!reviewId) {
        alert("Missing review ID.");
        return;
    }
    if (isNaN(rating) || rating < 1 || rating > 5) {
        alert("Rating must be between 1 and 5.");
        return;
    }
    if (text.length < 2) {
        alert("Review too short.");
        return;
    }

    const body = new URLSearchParams();
    body.append("review_id", reviewId);
    body.append("rating", rating);
    body.append("review_text", text);

    fetch("/RATEFLIXWEB/user/backend/update_review.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: body.toString()
    })
    .then(res => {
        if (!res.ok) throw new Error('Network response error');
        return res.json();
    })
    .then(data => {
        if (data.status !== "ok") {
            alert(data.message || "Could not update review.");
            return;
        }

        editModal.classList.add("hidden");
        loadReviews();
    })
    .catch(err => {
        console.error(err);
        alert("Something went wrong while updating.");
    });
});

// Delete (hide) review
document.addEventListener("click", (e) => {
    if (!e.target.classList.contains("delete-btn")) return;

    if (!confirm("Are you sure you want to delete this review?")) return;

    const reviewId = e.target.dataset.id;

    const body = new URLSearchParams();
    body.append("review_id", reviewId);

    fetch("/RATEFLIXWEB/user/backend/delete_review.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: body.toString()
    })
    .then(res => {
        if (!res.ok) throw new Error('Network response error');
        return res.json();
    })
    .then(data => {
        if (data.status !== "ok") {
            alert(data.message || "Could not delete review.");
            return;
        }

        loadReviews();
    })
    .catch(err => {
        console.error(err);
        alert("Something went wrong while deleting.");
    });
});

// Load reviews on page load
document.addEventListener("DOMContentLoaded", loadReviews);
// Open modal with shake if not logged in
openBtn.addEventListener("click", () => {
    if (!isLoggedIn) {
        loginRequiredModal.classList.remove("hidden");

        const box = loginRequiredModal.querySelector(".modal-box");
        box.classList.add("shake");
        setTimeout(() => box.classList.remove("shake"), 400);
        return;
    }

    modal.classList.remove("hidden");
});

closeLoginRequired.addEventListener("click", () => {
    loginRequiredModal.classList.add("hidden");
});

closeBtn.addEventListener("click", () => modal.classList.add("hidden"));

// Logout handler
const logoutBtn = document.getElementById("logoutBtn");
const logoutModal = document.getElementById("logoutModal");
const cancelLogout = document.getElementById("cancelLogout");

if (logoutBtn) {
    logoutBtn.addEventListener("click", () => {
        logoutModal.classList.remove("hidden");
        logoutModal.classList.add("flex");
    });
}

if (cancelLogout) {
    cancelLogout.addEventListener("click", () => {
        logoutModal.classList.add("hidden");
        logoutModal.classList.remove("flex");
    });
}

</script>
</body>
</html>
