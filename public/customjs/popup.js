document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('close-popup-btn');
    var popup = document.getElementById('not-found-popup');
    var popupContent = document.querySelector('.popup-content');
    if (btn && popup) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            popup.style.display = 'none';
            popup.classList.remove('d-flex');
            popup.classList.remove('align-items-center');
            popup.classList.remove('justify-content-center');
            popup.style.visibility = 'hidden';
        });
    }
    if (popup) {
        popup.addEventListener('click', function(e) {
            // Jika klik di area abu-abu (bukan di popup-content)
            if (!popupContent.contains(e.target)) {
                popup.style.display = 'none';
                popup.classList.remove('d-flex');
                popup.classList.remove('align-items-center');
                popup.classList.remove('justify-content-center');
                popup.style.visibility = 'hidden';
            }
        });
    }
    if (popupContent) {
        popupContent.style.pointerEvents = 'auto';
    }
});
