<?php
if(!class_exists('wpfyInquiryForm')){
    
    class wpfyInquiryForm {

        public function __construct() {

            // Add a button to the single product page
            add_action('woocommerce_product_meta_end', array($this, 'add_inquiry_button'), 20);

            // Enqueue scripts and styles
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_styles'));
        }


        /**
         * Add Inquiry Button Method
         */
        public function add_inquiry_button() {
            echo '<button id="wpfy-inquiry-button">' . esc_html__('Inquiry', 'wpfypi') . '</button>';

            // Include the inquiry form template
            include(WPFY_PY_PATH . 'templates/inquiry-form.php');
        }


        /**
         * Enqueue Scripts and Styles Method
         */
        public function enqueue_scripts_styles() {
            wp_enqueue_script('wpfy-inquiry-popup-js', WPFY_PY_URL . 'assets/js/inquiry-popup.js', array('jquery'), '1.0.0', true);
            wp_enqueue_style('wpfy-inquiry-popup-css', WPFY_PY_URL . 'assets/css/inquiry-popup.css');
        }

    }


}