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
          window.location.href = response.data.details_url;
        } else {
          alert("Error retrieving details.");
        }
      },
      error: function () {
        alert("Error retrieving details.");
      },
    });
  });

  // Handle click event for "Send Email Reply" button
  $(".send-email-reply").on("click", function () {
    // Toggle the "open" class on the email composer section
    $(".email-composer-section").toggleClass("open");
  });

  /**
   *
   * Manage Email Submission
   *
   */

  $(".email-submit").on("click", function (event) {
    event.preventDefault();

    var inquiryId = $(this).data("inquiry-id");
    var nonce = $(this).data("nonce");

    // Retrieve the content of the wp_editor
    var emailBody;
    if (
      typeof tinyMCE !== "undefined" &&
      tinyMCE.get("email_body") &&
      !tinyMCE.get("email_body").isHidden()
    ) {
      emailBody = tinyMCE.get("email_body").getContent();
    } else {
      emailBody = $("#email_body").val();
    }

    //resetFormFields
    resetFormFields();

    // Open the email composer modal
    openEmailComposer(inquiryId, nonce, emailBody);
  });

  function resetFormFields() {
    jQuery("#email-composer")[0].reset();
  }

  function openEmailComposer(inquiryId, nonce, emailBody) {
    // AJAX call to the PHP function handling email composition
    $.ajax({
      url: ajaxurl,
      type: "POST",
      data: {
        action: "compose_email",
        nonce: nonce,
        inquiry_id: inquiryId,
        email_body: emailBody,
      },
      success: function (response) {
        if (response.success) {
          console.log(response);
        } else {
          alert("Error opening email composer.");
        }
      },
      error: function () {
        alert("Error opening email composer.");
      },
    });
  }
});
