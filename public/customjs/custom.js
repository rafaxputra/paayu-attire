document.addEventListener('DOMContentLoaded', function () {
// Existing logic for delivery type and address field (if they exist)
const deliveryTypeSelect = document.getElementById('delivery_type');
const addressFieldDiv = document.getElementById('address-field');
const addressInput = document.getElementById('address');

if (deliveryTypeSelect && addressFieldDiv && addressInput) {
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
    toggleAddressField();
    deliveryTypeSelect.addEventListener('change', toggleAddressField);
}


// New logic for custom measurements visibility
const sizeChartOption = document.getElementById('size_chart_option');
const sizeChartTable = document.getElementById('sizeChartTable');
const customSizeInputsContainer = document.getElementById('customSizeInputs');

if (sizeChartOption && sizeChartTable && customSizeInputsContainer) {
    const customMeasurementFields = customSizeInputsContainer.querySelectorAll('input[type="number"]');

    function toggleSizeInputs() {
        if (sizeChartOption.value === 'custom') {
            sizeChartTable.classList.add('d-none'); // Hide size chart table
            sizeChartTable.classList.remove('d-block'); // Ensure d-block is removed
            customSizeInputsContainer.classList.remove('d-none'); // Show custom inputs
            customSizeInputsContainer.classList.add('d-flex'); // Ensure it's flex when visible
            customMeasurementFields.forEach(field => {
                field.setAttribute('required', 'required');
            });
        } else {
            sizeChartTable.classList.remove('d-none'); // Show size chart table
            sizeChartTable.classList.add('d-block'); // Ensure d-block is added
            customSizeInputsContainer.classList.add('d-none'); // Hide custom inputs
            customSizeInputsContainer.classList.remove('d-flex'); // Remove d-flex when hidden
            customMeasurementFields.forEach(field => {
                field.removeAttribute('required');
                field.value = ''; // Clear custom measurements if not needed
            });
        }
    }

    sizeChartOption.addEventListener('change', toggleSizeInputs);

    // Initial check on page load
    toggleSizeInputs();
}
});
