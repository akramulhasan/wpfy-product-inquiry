function closeInquiryPopup() {
    document.getElementById('wpfy-inquiry-popup').style.display = 'none';
    resetFormFields();
}

function resetFormFields() {
    $('#wpfy-inquiry-form')[0].reset();
}

jQuery(document).ready(function ($) {
    $('#wpfy-inquiry-button').on('click', function () {
        $('#wpfy-inquiry-popup').show();
    });

    $('#wpfy-inquiry-form').on('submit', function (e) {
        e.preventDefault();
        alert('Inquiry submitted!');
        closeInquiryPopup(); // Close the popup after submission
    });
});
