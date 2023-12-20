jQuery(document).ready(function($) {
    $('.view-details').on('click', function(event) {
        event.preventDefault();

        var inquiryId = $(this).data('inquiry-id');
        var nonce = ajax_object.nonce;

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'get_inquiry_details',
                nonce: nonce,
                inquiry_id: inquiryId,
            },
            success: function(response) {
                // Create a modal and append the response HTML
                var modalHtml = '<div class="modal">';
                modalHtml += '<div class="modal-content">' + response + '</div>';
                modalHtml += '<button class="close-modal">Close</button>';
                modalHtml += '</div>';

                // Append the modal to the body
                $('body').append(modalHtml);

                // Show the modal
                $('.modal').show();
            },
            error: function() {
                alert('Error retrieving details.');
            }
        });
    });

    // Close modal on clicking the close button
    $('body').on('click', '.close-modal', function() {
        $('.modal').remove();
    });
});
