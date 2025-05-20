// function to update price and total days
const minus = document.getElementById("Minus");
const plus = document.getElementById("Plus");
const count = document.getElementById("CountDays");
const durationInput = document.getElementById("DurationInput");
const totalPrice = document.getElementById("Total");

const productPrice = document.getElementById("productPrice");

const defaultPrice = parseFloat(productPrice.value);

function updateTotalPrice() {
    let subTotal = parseInt(durationInput.value) * defaultPrice;
    totalPrice.innerText = "Rp " + subTotal.toLocaleString("id-ID");
}

minus.addEventListener("click", function () {
    let currentCount = parseInt(count.innerText);
    if (currentCount > 1) {
        currentCount -= 1;
        count.innerText = currentCount;
        durationInput.value = currentCount;
        updateTotalPrice();
    }
});

plus.addEventListener("click", function () {
    let currentCount = parseInt(count.innerText);
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

// funtion nav & tabs like bootstrap
document.addEventListener("DOMContentLoaded", function () {
    window.openPage = function (pageName, elmnt) {
        let i, tabcontent, tablinks;

        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.add("hidden");
        }

        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active", "ring-2", "ring-[#FCCF2F]");
        }

        document.getElementById(pageName).classList.remove("hidden");
        elmnt.classList.add("active", "ring-2", "ring-[#FCCF2F]");
    };

    toggleAddressField();
});

// Function to toggle delivery address field based on select value
function toggleAddressField() {
    const deliveryTypeSelect = document.getElementById("delivery_type");
    const addressFieldDiv = document.getElementById("address-field");
    const addressTextarea = document.getElementById("address");

    if (deliveryTypeSelect.value === "delivery") {
        addressFieldDiv.classList.remove("d-none"); // Show the div
        addressFieldDiv.classList.add("d-flex"); // Ensure it's a flex container
        addressTextarea.setAttribute('required', 'required');
    } else {
        addressFieldDiv.classList.add("d-none"); // Hide the div
        addressFieldDiv.classList.remove("d-flex"); // Remove flex class when hidden
        addressTextarea.removeAttribute('required');
        addressTextarea.value = "";
    }
}
