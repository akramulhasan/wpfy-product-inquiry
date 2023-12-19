jQuery(document).ready(function($) {
    $('.view-details').on('click', function() {
        var inquiryId = $(this).data('inquiry-id');
        console.log(inquiryId);

        // Here we would make an AJAX call to the above PHP function
        // $.ajax({
        //     url: ajaxurl, // This is a global variable available in the admin
        //     type: 'POST',
        //     data: {
        //         action: 'get_inquiry_details',
        //         nonce: '<?php echo wp_create_nonce("get_inquiry_details_nonce"); ?>',
        //         inquiry_id: inquiryId,
        //     },
        //     success: function(response) {
        //         // Populate the details div with the response
        //         $('#inquiry-details').html(response).show();
        //     },
        //     error: function() {
        //         alert('Error retrieving details.');
        //     }
        // });
    });
});