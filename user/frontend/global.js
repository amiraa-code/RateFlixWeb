//where rquest will be made
document.addEventListener('DOMContentLoaded', requestCategories)
function requestCategories(){
    //API
    fetch('http://localhost:8081/handler.php')
    //passing the promise that have been sent from the srver
    .then( (response)=> response.json() )
.then((data) => {
console.log(data)
})




}