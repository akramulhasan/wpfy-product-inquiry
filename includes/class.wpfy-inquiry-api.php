<?php
if(!class_exists('wpfyInquiryApi')){
    
    class wpfyInquiryApi {

        public function __construct() {

            add_action('rest_api_init', array($this, 'register_rest_routes'));

        }

        /**
         * Register REST API routes (register_rest_routes method description)
         */
        public function register_rest_routes() {
            register_rest_route('wpfypi/v1', '/submit-inquiry', array(
                'methods' => 'POST',
                'callback' => array($this, 'handle_inquiry_submission'),
                'permission_callback' => array($this, 'inquiry_permissions_check'),
            ));
        }

        /**
         * Method Description of handle_inquiry_submission
         */

         public function handle_inquiry_submission(WP_REST_Request $request) {
            
            // First, sanitize and validate incoming data
            $name = sanitize_text_field($request->get_param('name'));
            $email = sanitize_email($request->get_param('email'));
            $message = sanitize_textarea_field($request->get_param('message'));
        
            // Optional: Validate the email address (if necessary)
            if (!is_email($email)) {
                return new WP_Error('invalid_email', 'The provided email address is invalid', array('status' => 400));
            }
        
            // Now, prepare the data for inserting as a custom post type
            $inquiry_data = array(
                'post_title'    => $name, // Could also be a generated title or left empty
                'post_content'  => $message,
                'post_status'   => 'publish', // Or another status (e.g., 'draft', 'pending')
                'post_type'     => 'wpfypi_inquiry', // Your registered custom post type
            );
        
            // Insert the post into the database
            $post_id = wp_insert_post($inquiry_data);
        
            // If submission was successful, save additional data as post meta
            if ($post_id != 0) {
                update_post_meta($post_id, 'wpfypi_email', $email);
        
                // Add any additional post meta as needed
                // update_post_meta($post_id, 'meta_key', $meta_value);
        
                // Return a success response
                $response = array(
                    'status' => 'success',
                    'message' => 'Inquiry submitted successfully.',
                    'post_id' => $post_id
                );
            } else {
                // In case of an error during post creation, return a WP_Error
                return new WP_Error('db_insert_error', 'Failed to submit inquiry', array('status' => 500));
            }
        
            return rest_ensure_response($response);
        }
        
        
        /**
         * Permission callback to check if a request has valid data.
         */
        public function inquiry_permissions_check($request) {
            // Check if all the required fields are provided
            if ( ! isset( $request['name'] ) || empty( $request['name'] ) ) {
                return new WP_Error( 'rest_missing_name', __( 'Missing name field', 'wpfypi' ), array( 'status' => 400 ) );
            }
            if ( ! isset( $request['email'] ) || empty( $request['email'] ) ) {
                return new WP_Error( 'rest_missing_email', __( 'Missing email field', 'wpfypi' ), array( 'status' => 400 ) );
            }
            if ( ! isset( $request['message'] ) || empty( $request['message'] ) ) {
                return new WP_Error( 'rest_missing_message', __( 'Missing message field', 'wpfypi' ), array( 'status' => 400 ) );
            }

            // Optional: Add additional validation for email
            if ( ! is_email( $request['email'] ) ) {
                return new WP_Error( 'rest_invalid_email', __( 'Invalid email address', 'wpfypi' ), array( 'status' => 400 ) );
            }

            // Optional: Implement any further permissions checks - e.g., current_user_can(), nonce checks, etc
            // If all checks pass, return true
            return true;
        }

    }


}