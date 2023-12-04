<?php
if(!class_exists('wpfyInquiryForm')){
    
    class wpfyInquiryForm{

        public function __construct() {

            // Add a button to the single product page
            add_action('woocommerce_product_meta_end', array($this, 'add_inquiry_button'), 20);

            // Enqueue scripts and styles
            //add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_styles'));
        }


        /**
         * Add Inquiry Button Method
         */
        public function add_inquiry_button() {
            echo '<button id="wpfy-inquiry-button">' . esc_html__('Inquiry', 'wpfypi') . '</button>';
        }


        /**
         * Enqueue Scripts and Styles Method
         */
        public function enqueue_scripts_styles() {
            wp_enqueue_script('wpfy-inquiry-popup', WPFY_PY_URL . 'assets/inquiry-popup.js', array('jquery'), '1.0.0', true);
            wp_enqueue_style('wpfy-inquiry-popup', WPFY_PY_URL . 'assets/inquiry-popup.css');
        }

    }


}