// Close button + backdrop click for the "not found" popup on the
// transactions (Check Booking) page.
//
// Runs immediately instead of waiting for DOMContentLoaded: this file
// is loaded inside the page content, after the popup markup, so the
// elements already exist, and waiting for DOMContentLoaded proved
// fragile — on the forwarded site the night-mode toggle and the
// gallery died the same way.
(function () {
    function init() {
        var btn = document.getElementById('close-popup-btn');
        var popup = document.getElementById('not-found-popup');
        var popupContent = document.querySelector('.popup-content');

        if (!popup) {
            return false;
        }

        function hidePopup() {
            popup.style.display = 'none';
            popup.classList.remove('d-flex');
            popup.classList.remove('align-items-center');
            popup.classList.remove('justify-content-center');
            popup.style.visibility = 'hidden';
        }

        if (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                hidePopup();
            });
        }

        popup.addEventListener('click', function (e) {
            // Jika klik di area abu-abu (bukan di popup-content)
            if (popupContent && !popupContent.contains(e.target)) {
                hidePopup();
            }
        });

        if (popupContent) {
            popupContent.style.pointerEvents = 'auto';
        }

        return true;
    }

    if (!init() && document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    }
})();
