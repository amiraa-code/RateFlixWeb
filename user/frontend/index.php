<!DOCTYPE html>
<html lang="en">

<?php 
session_start();
include "./components/head.php"; 
?>

<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col">

<?php include "./components/header.php"; ?>
<!-- Logout Modal -->
<div id="logoutModal"
    class="fixed inset-0 bg-black/60 hidden justify-center items-center z-50">

    <div class="bg-slate-800 p-6 rounded-xl w-80 text-center shadow-xl">

        <h2 class="text-xl font-bold mb-4">Logout?</h2>
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

<script>
// Initialize logout functionality safely
document.addEventListener('DOMContentLoaded', () => {
    const logoutBtn = document.getElementById("logoutBtn");
    const logoutModal = document.getElementById("logoutModal");
    const cancelLogout = document.getElementById("cancelLogout");

    // Only add listeners if elements exist
    if (logoutBtn && logoutModal && cancelLogout) {
        logoutBtn.addEventListener("click", () => {
            logoutModal.classList.remove("hidden");
            logoutModal.classList.add("flex");
        });

        cancelLogout.addEventListener("click", () => {
            logoutModal.classList.add("hidden");
            logoutModal.classList.remove("flex");
        });
    }
});
</script>


<main class="pt-40 md:pt-28">

  <!-- Banner -->
  <section class="banner w-full overflow-hidden">
    <div class="swiper w-full">
      <div class="swiper-wrapper"></div>
    </div>
  </section>

</main>

<?php include "./components/footer.php"; ?>

<script src="global.js"></script>
</body>
</html>
