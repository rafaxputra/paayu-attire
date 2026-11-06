// Product slider — only initialise when a .swiper element and the Swiper
// library are actually present. The catalogue currently has no slider
// markup at all, so the previous unguarded `new Swiper('.swiper', ...)`
// served no purpose; the Swiper CDN assets it needed have been removed
// from the blades as well. Re-add the Swiper CDN links and a .swiper
// element if a slider is ever wanted — this file will pick it up.
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Swiper !== 'undefined' && document.querySelector('.swiper')) {
        new Swiper('.swiper', {
            // Optional parameters
            direction: 'horizontal',
            spaceBetween: 20,
            slidesPerView: 'auto',
        });
    }
});
