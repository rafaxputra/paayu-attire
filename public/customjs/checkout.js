// Show the chosen payment-proof filename under the file input.
//
// Previously this targeted #Proof / #Upload-btn, which do not exist on
// the checkout page (the real input is #payment_proof), so the script
// threw "Cannot read properties of null" on every checkout load and the
// filename was never shown. Now it targets #payment_proof and updates
// the #payment-proof-filename hint, and does nothing if either element
// is missing.
document.addEventListener('DOMContentLoaded', function () {
    var fileInput = document.getElementById('payment_proof');
    var hint = document.getElementById('payment-proof-filename');

    if (!fileInput || !hint) {
        return;
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
});
