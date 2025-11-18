//where rquest will be made
document.addEventListener('DOMContentLoaded', requestCategories)
document.addEventListener('DOMContentLoaded', requestBanner)
document.addEventListener('DOMContentLoaded', requestMovies('Action'))
function requestCategories(){
    //API
    fetch('/RATEFLIXWEB/user/backend/handler.php', {
        method: "GET",
    })
    //passing the promise that have been sent from the srver
    .then( (response)=> response.json() )
    .then((data) => {
      //  console.log(data);
      //displaying categories from dbs to website
      const nav = document.querySelector('.navigation');
      if(data.category){
        const ul = document.createElement('ul');
        ul.className = "flex gap-6 text-white text-lg font-semibold";

        data.category.forEach(cat => {
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
    .catch((err) => console.log(err));
}

function requestBanner(){
    fetch('/RATEFLIXWEB/user/backend/banner.php', {
        method: "GET",
    })
    .then( (response)=> response.json() )
    .then((data) => {
     console.log('Banner:', data);
      if(data.banner){
        const banner = data.banner
        banner.forEach(banner=>{
          
          const slide = document.createElement('div')
          slide.className =
            "swiper-slide h-[55vh] bg-cover bg-center flex flex-col justify-end p-8 text-white";
          slide.style.backgroundImage = `url('http://localhost/rateflixweb${banner.image}')`;

          const h3 = document.createElement('h3')
          h3.textContent=banner.name
          h3.className = "text-3xl font-bold mb-2 drop-shadow-lg";

          const p = document.createElement('p')
          p.textContent=banner.description
          p.className = "text-lg mb-4 drop-shadow";

          const button = document.createElement('button')
          button.textContent ='Browse more'
          button.className = "bg-accent w-fit px-4 py-2 rounded shadow hover:bg-accent/90";

          slide.appendChild(h3)
          slide.appendChild(p)
          slide.appendChild(button)

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



function requestMovies(category) {
  fetch(`/RATEFLIXWEB/user/backend/fetchmovies.php?category=${encodeURIComponent(category)}`)
    .then((response) => response.json())
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
            <div class="w-full h-80 bg-black flex items-center justify-center">
              <img 
                src="${movie.Poster !== 'N/A' ? movie.Poster : '/rateflixweb/images/placeholder.jpg'}"
                alt="${movie.Title}"
                class="max-h-full max-w-full object-contain"
              >
            </div>
            <div class="p-3">
              <h3 class="text-lg font-semibold">${movie.Title}</h3>
              <p class="text-sm text-gray-300">${movie.Year}</p>
            </div>
          `;


          grid.appendChild(card);
        });

        movieSection.appendChild(grid);
        document.querySelector('main').appendChild(movieSection);
      }
    })
    .catch((err) => console.error(err));
}
