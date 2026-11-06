// Product slider — only initialise when a .swiper element and the Swiper
// library are actually present. The catalogue currently has no slider
// markup at all, so the previous unguarded `new Swiper('.swiper', ...)`
// served no purpose; the Swiper CDN assets it needed have been removed
// from the blades as well. Re-add the Swiper CDN links and a .swiper
// element if a slider is ever wanted — this file will pick it up.
//
// Runs immediately instead of waiting for DOMContentLoaded: this file
// is loaded after the content, so the card images already exist, and
// waiting for DOMContentLoaded proved fragile — on the forwarded site
// the night-mode toggle and the gallery died the same way.
(function () {
    function init() {
        if (typeof Swiper !== 'undefined' && document.querySelector('.swiper')) {
            new Swiper('.swiper', {
                // Optional parameters
                direction: 'horizontal',
                spaceBetween: 20,
                slidesPerView: 'auto',
            });
        }

        // Stop the shimmer placeholder on each product card once its image
        // has loaded, so the loading state ends instead of shimmering forever
        // underneath the photo.
        document.querySelectorAll('.card-img-top').forEach(function (img) {
            if (img.complete && img.naturalWidth > 0) {
                img.classList.add('img-loaded');
            } else {
                img.addEventListener('load', function () {
                    img.classList.add('img-loaded');
                });
            }
        });

        return true;
    }

    if (!init() && document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    }
})();
