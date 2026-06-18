

window.addEventListener("DOMContentLoaded", () => {

    const swipervar = document.querySelector('.swiper');

    if (swipervar) {
        const swiper = new Swiper('.swiper-todays-book', {
            slidesPerView: 5,
            slidesPerGroupSkip: 1,
            slidesOffsetBefore: 50,
            slidesOffsetAfter: 50,
            normalizeSlideIndex: false,
            effect: "coverflow",
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            }
        });
    }

});