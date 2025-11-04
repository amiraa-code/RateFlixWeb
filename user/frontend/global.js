//where rquest will be made
document.addEventListener('DOMContentLoaded', requestCategories)
function requestCategories(){
    //API
    fetch('/RATEFLIXWEB/user/backend/handler.php', {
        method: "GET",
    })
    //passing the promise that have been sent from the srver
    .then( (response)=> response.json() )
    .then((data) => {
        console.log(data);
        })
        .catch((err) => console.log(err));



}