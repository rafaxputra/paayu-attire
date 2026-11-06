// Booking page: +/- duration stepper + live total price.
//
// Removed: openPage() tab switching and toggleAddressField() — they
// targeted .tabcontent / .tablink / #delivery_type / #address-field,
// none of which exist on this page, so toggleAddressField() threw
// "Cannot read properties of null" on every booking-page load.
// The price stepper below is guarded too, so a missing element can no
// longer kill the whole script.
(function () {
    var minus = document.getElementById("Minus");
    var plus = document.getElementById("Plus");
    var count = document.getElementById("CountDays");
    var durationInput = document.getElementById("DurationInput");
    var totalPrice = document.getElementById("Total");
    var productPrice = document.getElementById("productPrice");

    if (!minus || !plus || !count || !durationInput || !totalPrice || !productPrice) {
        return;
    }

    var defaultPrice = parseFloat(productPrice.value);

    function updateTotalPrice() {
        var subTotal = parseInt(durationInput.value, 10) * defaultPrice;
        totalPrice.innerText = "Rp " + subTotal.toLocaleString("id-ID");
    }

    minus.addEventListener("click", function () {
        var currentCount = parseInt(count.innerText, 10);
        if (currentCount > 1) {
            currentCount -= 1;
            count.innerText = currentCount;
            durationInput.value = currentCount;
            updateTotalPrice();
        }
    });

    plus.addEventListener("click", function () {
        var currentCount = parseInt(count.innerText, 10);
        currentCount += 1;
        count.innerText = currentCount;
        durationInput.value = currentCount;
        updateTotalPrice();
    });

    durationInput.addEventListener("input", function () {
        count.innerText = durationInput.value;
        updateTotalPrice();
    });

    updateTotalPrice();
})();
