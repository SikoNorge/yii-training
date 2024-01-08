// user-carousel.js

document.addEventListener('DOMContentLoaded', function () {
    var myCarousel = document.getElementById('user-carousel');
    if (myCarousel) {
        new bootstrap.Carousel(myCarousel);
    }
});