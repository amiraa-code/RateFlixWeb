//where rquest will be made
document.addEventListener('DOMContentLoaded', requestCategories)
document.addEventListener('DOMContentLoaded', requestBanner)
document.addEventListener('DOMContentLoaded', () => requestMovies('Horror'));
function requestCategories(){
    //API
    fetch('/RATEFLIXWEB/user/backend/handler.php', {
        method: "GET",
    })
    //passing the promise that have been sent from the srver
    .then( (response)=> {
        if (!response.ok) throw new Error('Network response error');
        return response.json();
    } )
    .then((data) => {
      //  console.log(data);
      //displaying categories from dbs to website
      const nav = document.querySelector('.navigation');
      if(data.categories){
        const ul = document.createElement('ul');
        ul.className = "flex gap-6 text-white text-lg font-semibold";

        data.categories.forEach(cat => {
            const li = document.createElement('li');
            li.className = "cursor-pointer bg-primaryLight px-4 py-2 rounded-full hover:bg-accent transition";
            li.textContent = cat;

            li.addEventListener('click', () => {
                requestMovies(cat);
            });

            ul.appendChild(li);
        });

        nav.innerHTML = "";
        nav.append(ul);
      }
    })
    .catch((err) => console.error('Categories error:', err));
}

// fetch banner from backend

function requestBanner() {
  fetch('/RATEFLIXWEB/user/backend/banner.php', {
    method: "GET",
  })
    .then((response) => {
        if (!response.ok) throw new Error('Network response error');
        return response.json();
    })
    .then((data) => {
      console.log('Banner:', data);
      if (data.banner) {
        const banner = data.banner;
        banner.forEach(banner => {

          const slide = document.createElement('div');
          slide.className =
            "swiper-slide h-[55vh] bg-cover bg-center flex flex-col justify-end p-8 text-white";
          slide.style.backgroundImage = `url('http://localhost/rateflixweb${banner.image}')`;

          const h3 = document.createElement('h3');
          h3.textContent = banner.name;
          h3.className = "text-3xl font-bold mb-2 drop-shadow-lg";

          const p = document.createElement('p');
          p.textContent = banner.description;
          p.className = "text-lg mb-4 drop-shadow";

          slide.appendChild(h3);
          slide.appendChild(p);

          const swiperWrapper = document.querySelector(".swiper-wrapper");
          swiperWrapper.append(slide);
        });

        callCarousal();
      }
    })
    .catch((err) => console.log(err));
}

function callCarousal (){
  const swiper = new Swiper('.swiper', {
  // Optional parameters
  direction: 'horizontal',
  loop: true,
  autoplay:{
    delay: 4000,
    disableoOnInteraction: false
  }
});
}

//fetch movies based on category

function requestMovies(category) {
  fetch(`/RATEFLIXWEB/user/backend/fetchmovies.php?category=${encodeURIComponent(category)}`)
    .then((response) => {
        if (!response.ok) throw new Error('Network response error');
        return response.json();
    })
    .then((data) => {
      console.log("Movies:", data);

      // claer any existing movie sec
      const oldSection = document.querySelector('.movie-section');
      if (oldSection) oldSection.remove();

      // only proceed if  got mvies
      if (data.status === "ok" && data.movies) {
        const movieSection = document.createElement('section');
        movieSection.className = 'movie-section mt-10 px-6';

        const heading = document.createElement('h2');
        heading.textContent = `${category} Movies`;
        heading.className = "text-3xl font-bold mb-6 ml-1";
        movieSection.appendChild(heading);

        const grid = document.createElement('div');
        grid.className =
          "grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6";

        data.movies.forEach((movie) => {
          const card = document.createElement('a');
          card.href = `/RATEFLIXWEB/user/frontend/movie.php?imdbID=${movie.imdbID}`;
          card.className =
            "block bg-gray-900 text-white rounded-lg overflow-hidden shadow-lg hover:scale-105 transition transform";

          card.innerHTML = `
            <div class="w-full aspect-[2/3] bg-black overflow-hidden">
              <img 
                src="${movie.poster}"
                alt="${movie.title}"
                class="w-full h-full object-cover"
              >
            </div>
            <div class="p-3">
              <h3 class="text-lg font-semibold">${movie.title}</h3>
              <p class="text-sm text-gray-300">${movie.release_date}</p>
            </div>
          `;


          grid.appendChild(card);
        });

        movieSection.appendChild(grid);
        document.querySelector('main').appendChild(movieSection);
      }
    })
    .catch((err) => console.error('Movies error:', err));
}
// Search functionality
const searchInput = document.getElementById("searchInput");
const searchForm = document.getElementById("searchForm");
const searchCategory = document.getElementById("searchCategory");
const searchYear = document.getElementById("searchYear");
let typingTimer;

// Populate category dropdown on page load
document.addEventListener('DOMContentLoaded', () => {
  fetch('/RATEFLIXWEB/user/backend/handler.php')
    .then(res => res.json())
    .then(data => {
      if (data.categories && searchCategory) {
        // Remove all except first option
        searchCategory.innerHTML = '<option value="">All Categories</option>';
        data.categories.forEach(cat => {
          const opt = document.createElement('option');
          opt.value = cat;
          opt.textContent = cat;
          searchCategory.appendChild(opt);
        });
      }
    });
});

// run on submit
searchForm.addEventListener("submit", function(e) {
  e.preventDefault();
  doSearch();
});

// live search (title only)
searchInput.addEventListener("keyup", () => {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doSearch, 400); // debounce
});

function doSearch() {
  const title = searchInput.value.trim();
  const category = searchCategory.value.trim();
  const year = searchYear.value.trim();

  // Build query string
  const params = [];
  if (title) params.push(`title=${encodeURIComponent(title)}`);
  if (category) params.push(`category=${encodeURIComponent(category)}`);
  if (year) params.push(`year=${encodeURIComponent(year)}`);
  if (params.length === 0) return;

  fetch(`/RATEFLIXWEB/user/backend/search.php?${params.join('&')}`)
    .then(res => res.json())
    .then(data => {
      const oldSection = document.querySelector('.movie-section');
      if (oldSection) oldSection.remove();

      const movieSection = document.createElement('section');
      movieSection.className = 'movie-section mt-10 px-6';

      let headingText = 'Search results';
      if (title) headingText += ` for "${title}"`;
      if (category) headingText += ` in ${category}`;
      if (year) headingText += ` (${year})`;
      const heading = document.createElement('h2');
      heading.textContent = headingText;
      heading.className = "text-3xl font-bold mb-6 ml-1";
      movieSection.appendChild(heading);

      const grid = document.createElement('div');
      grid.className =
        "grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6";

      if (!data.movies || data.movies.length === 0) {
        grid.innerHTML = `<p class=\"text-gray-300\">No matches found.</p>`;
      } else {
        data.movies.forEach(movie => {
          const card = document.createElement('a');
          card.href = `/RATEFLIXWEB/user/frontend/movie.php?imdbID=${movie.imdbID}`;
          card.className =
            "block bg-gray-900 text-white rounded-lg overflow-hidden shadow-lg hover:scale-105 transition";

          card.innerHTML = `
            <div class=\"w-full h-80 bg-black flex items-center justify-center\">
              <img 
                src=\"${movie.poster}\"
                alt=\"${movie.title}\"
                class=\"max-h-full max-w-full object-contain\"
              >
            </div>
            <div class=\"p-3\">
              <h3 class=\"text-lg font-semibold\">${movie.title}</h3>
              <p class=\"text-sm text-gray-300\">${movie.release_date}</p>
              <p class=\"text-xs text-gray-400\">${movie.category_name}</p>
            </div>
          `;
          grid.appendChild(card);
        });
      }

      movieSection.appendChild(grid);
      document.querySelector('main').appendChild(movieSection);
    })
    .catch(err => console.error(err));
}
