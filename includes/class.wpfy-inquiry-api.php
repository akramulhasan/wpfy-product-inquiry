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