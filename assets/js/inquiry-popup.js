function closeInquiryPopup() {
    document.getElementById('wpfy-inquiry-popup').style.display = 'none';
    resetFormFields();
}

function resetFormFields() {
    jQuery('#wpfy-inquiry-form')[0].reset();
}

jQuery(document).ready(function ($) {
    $('#wpfy-inquiry-button').on('click', function () {
        $('#wpfy-inquiry-popup').show();
    });

    $('#wpfy-inquiry-form').on('submit', function (e) {
        e.preventDefault();

        // Gather form data
        var formData = {
            'name': $('#wpfy-inquiry-name').val(),
            'email': $('#wpfy-inquiry-email').val(),
            'message': $('#wpfy-inquiry-message').val(),
            'productName': $('#wpfy-inquiry-product-name').val(),
        };

        // Submit form data to the REST API
        $.ajax({
            url: '/wp-json/wpfypi/v1/submit-inquiry', // Your custom endpoint
            method: 'POST',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: function(response) {

            // create the success message markup
            $('#wpfy-inquiry-success')
                .html('Inquiry submitted successfully! We will get back to you soon.')
                .show();
            resetFormFields();
            // Close the popup after a delay
            setTimeout(closeInquiryPopup, 5000); // Close the popup after 5 seconds
            },

            error: function(response) {
                console.error(response);
                alert('There was a problem submitting your inquiry. Please try again.');
            }
        });
    });
});