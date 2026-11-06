// Show the chosen payment-proof filename under the file input.
//
// Previously this targeted #Proof / #Upload-btn, which do not exist on
// the checkout page (the real input is #payment_proof), so the script
// threw "Cannot read properties of null" on every checkout load and the
// filename was never shown. Now it targets #payment_proof and updates
// the #payment-proof-filename hint, and does nothing if either element
// is missing.
//
// Runs immediately instead of waiting for DOMContentLoaded: this file
// is loaded after the content, so the input already exists, and
// waiting for DOMContentLoaded proved fragile — on the forwarded site
// the night-mode toggle and the gallery died the same way.
(function () {
    function init() {
        var fileInput = document.getElementById('payment_proof');
        var hint = document.getElementById('payment-proof-filename');

        if (!fileInput || !hint) {
            return false;
        }

        fileInput.addEventListener('change', function () {
            var file = this.files && this.files[0];

            if (file) {
                hint.textContent = 'Selected file: ' + file.name;
                hint.classList.add('fw-semibold');
            } else {
                hint.textContent = 'Accepted formats: JPG, PNG';
                hint.classList.remove('fw-semibold');
            }
        });

        return true;
    }

    if (!init() && document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    }
})();
