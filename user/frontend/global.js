//where rquest will be made
document.addEventListener('DOMContentLoaded', requestCategories)
document.addEventListener('DOMContentLoaded', requestBanner)
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
            // li.addEventListener('click', getCategoryMovies);
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
