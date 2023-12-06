function closeInquiryPopup() {
    document.getElementById('wpfy-inquiry-popup').style.display = 'none';
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