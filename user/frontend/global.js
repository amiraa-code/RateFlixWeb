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
        data.category.forEach(cat => {
            const li = document.createElement('li');
            li.className = cat;
            li.textContent = cat;
            li.addEventListener('click', () => {
            requestMovies(cat);
          });
            ul.appendChild(li);
        });
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
          slide.className='swiper-slide'
          slide.style.backgroundImage = `url('http://localhost/rateflixweb${banner.image}')`;
          slide.style.height = "45vh" ;
          slide.style.backgroundSize ='cover';
          slide.style.backgroundPosition = "center";
          const h3 = document.createElement('h3')
          h3.textContent=banner.name
          const p = document.createElement('p')
          p.textContent=banner.description
          const button = document.createElement('button')
          button.textContent ='Browse more'
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

      // Clear any existing movie section
      const oldSection = document.querySelector('.movie-section');
      if (oldSection) oldSection.remove();

      // Only proceed if we got movies
      if (data.status === "ok" && data.movies) {
        const movieSection = document.createElement('section');
        movieSection.className = 'movie-section';

        const heading = document.createElement('h2');
        heading.textContent = `${category} Movies`;
        movieSection.appendChild(heading);

        const grid = document.createElement('div');
        grid.className = 'movie-grid';

        data.movies.forEach((movie) => {
          const card = document.createElement('div');
          card.className = 'movie-card';
          card.innerHTML = `
            <img src="${movie.Poster !== 'N/A' ? movie.Poster : '/rateflixweb/images/placeholder.jpg'}" alt="${movie.Title}">
            <h3>${movie.Title}</h3>
            <p>${movie.Year}</p>
          `;
          grid.appendChild(card);
        });

        movieSection.appendChild(grid);
        document.querySelector('main').appendChild(movieSection);
      }
    })
    .catch((err) => console.error(err));
}
