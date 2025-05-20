document.addEventListener('DOMContentLoaded', function () {
    const deliveryTypeSelect = document.getElementById('delivery_type');
    const addressFieldDiv = document.getElementById('address-field');
    const addressInput = document.getElementById('address');

    function toggleAddressField() {
        if (deliveryTypeSelect.value === 'delivery') {
            addressFieldDiv.classList.remove('d-none'); // Show the div
            addressInput.setAttribute('required', 'required');
        } else {
            addressFieldDiv.classList.add('d-none'); // Hide the div
            addressInput.removeAttribute('required');
            addressInput.value = ""; // Clear the address when hidden
        }
    }

    // Set initial state
    toggleAddressField();

    // Add event listener for changes
    deliveryTypeSelect.addEventListener('change', toggleAddressField);
});
