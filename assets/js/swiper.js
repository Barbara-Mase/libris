
const Swiper = require('../../node_modules/swiper/swiper-bundle');

// configure Swiper to use modules
Swiper.use([Navigation, Pagination]);
window.addEventListener("DOMContentLoaded", () => {

    const swiper = new Swiper('.swiper', {
        slidesPerView: 4,
        spaceBetween: 30,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        },
        scrollbar: {
            el: ".swiper-scrollbar",
            draggable: true
        }
    });
});
