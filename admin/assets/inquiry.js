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
                if (response.success) {
                    // Extract data object from the response
                    var dataObj = response.data;

                    // Build a 2-column table in HTML
                    var tableHtml = '<table class="two-column-table">';
                    $.each(dataObj, function(key, value) {
                        tableHtml += '<tr><td>' + key + '</td><td>' + value + '</td></tr>';
                    });
                    tableHtml += '</table>';

                    // Create a modal and append the table HTML
                    var modalHtml = '<div class="modal">';
                    modalHtml += '<div class="modal-content">' + tableHtml + '</div>';
                    modalHtml += '<button class="close-modal">Close</button>';
                    modalHtml += '</div>';

                    // Append the modal to the body
                    $('body').append(modalHtml);

                    // Show the modal
                    $('.modal').show();
                } else {
                    alert('Error retrieving details.');
                }
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
