jQuery(document).ready(function ($) {
  $(".view-details").on("click", function (event) {
    event.preventDefault();

    var inquiryId = $(this).data("inquiry-id");
    var nonce = ajax_object.nonce;

    $.ajax({
      url: ajaxurl,
      type: "POST",
      data: {
        action: "get_inquiry_details",
        nonce: nonce,
        inquiry_id: inquiryId,
      },
      success: function (response) {
        if (response.success) {
          // Redirect to the custom settings page URL with the inquiry ID as a parameter
          //window.location.href = response.data.details_url;
        } else {
          alert("Error retrieving details.");
        }
      },
      error: function () {
        alert("Error retrieving details.");
      },
    });
  });

  // Close modal on clicking the close button
  $("body").on("click", ".close-modal", function () {
    $(".modal").remove();
  });
});
