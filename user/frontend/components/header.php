<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Build return URL - just the relative path with query string
$return_to = $_SERVER['REQUEST_URI'] ?? '/RATEFLIXWEB/user/frontend/index.php';
?>

<header class="fixed top-0 left-0 right-0 z-50 bg-primary text-white shadow-md">
    <div class="flex items-center justify-between px-6 h-16 w-full">

        <!-- Logo  -->
        <a href="/RATEFLIXWEB/user/frontend/index.php" class="flex items-center">
            <img src="/RATEFLIXWEB/images/logo.svg" class="h-16 w-auto" alt="RateFlix Logo">
        </a>

        <!-- Search - hide on mobile -->
        <form id="searchForm" class="hidden md:flex items-center bg-white rounded-md overflow-hidden shadow">
            <input 
                type="search" 
                name="title"
                id="searchInput"
                placeholder="Title..."
                class="px-4 py-2 text-gray-700 outline-none w-40"
                autocomplete="off"
            />
            <select name="category" id="searchCategory" class="px-2 py-2 text-gray-700 bg-white border-l border-gray-300 outline-none">
                <option value="">All Categories</option>
            </select>
            <input 
                type="number" 
                name="year"
                id="searchYear"
                placeholder="Year"
                min="1900" max="2099"
                class="px-2 py-2 text-gray-700 bg-white border-l border-gray-300 outline-none w-20"
            />
            <button 
                type="submit" 
                class="px-4 py-2 bg-accent text-white font-semibold hover:bg-accent/90"
            >
                Search
            </button>
        </form>



        <!-- User Section -->
    <div class="flex items-center gap-6 text-xl">
    <?php if (!isset($_SESSION['user_id'])): ?>
        <!-- LOGIN -->
        <a href="/RATEFLIXWEB/user/frontend/login.php?return_to=<?php echo urlencode($return_to); ?>" 
        class="relative group cursor-pointer">
            <i class="fa-solid fa-right-to-bracket hover:text-accent transition"></i>

            <span class="absolute left-1/2 -translate-x-1/2 top-8
                bg-slate-800 text-white text-sm px-2 py-1 rounded
                opacity-0 scale-90 group-hover:opacity-100 group-hover:scale-100
                pointer-events-none transition-all duration-200
                whitespace-nowrap shadow-lg">
                Login
            </span>
        </a>

        <!-- REGISTER -->
        <a href="/RATEFLIXWEB/user/frontend/register.php?return_to=<?php echo urlencode($return_to); ?>" 
        class="relative group cursor-pointer">
            <i class="fa-solid fa-user-plus hover:text-accent transition"></i>

            <span class="absolute left-1/2 -translate-x-1/2 top-8
                bg-slate-800 text-white text-sm px-2 py-1 rounded
                opacity-0 scale-90 group-hover:opacity-100 group-hover:scale-100
                pointer-events-none transition-all duration-200
                whitespace-nowrap shadow-lg">
                Register
            </span>
        </a>

    <?php else: ?>

        <div class="flex items-center gap-2 text-base">
            <i class="fa-solid fa-user-check text-accent"></i>
            <span class="font-medium">
                Oh Hey! <?= htmlspecialchars($_SESSION['username']); ?>
            </span>
        </div>

        <!-- LOGOUT -->
        <a id="logoutBtn"
        class="relative group hover:text-accent transition cursor-pointer">

        <i class="fa-solid fa-right-from-bracket"></i>

        <span class="absolute left-1/2 -translate-x-1/2 top-8
            bg-slate-800 text-white text-sm px-2 py-1 rounded
            opacity-0 scale-90 group-hover:opacity-100 group-hover:scale-100
            pointer-events-none transition-all duration-200
            whitespace-nowrap shadow-lg">
            Logout
        </span>
        </a>



    <?php endif; ?>

</div>
</div>

    <!-- Categories by JS -->
    <nav class="navigation bg-primaryLight h-12 flex items-center justify-center w-full"></nav>
</header>
