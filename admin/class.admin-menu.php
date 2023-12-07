<?php
if(!class_exists('wpfyAdminMenu')){

    class wpfyAdminMenu {

        public function __construct(){
            add_action('admin_menu', array($this, 'add_menu'));
        }

        public function add_menu(){
            add_menu_page(
            'WPFY Product Inquiry Settings', 
            'Product Inquiry', 
            'manage_options', 
            'wpfy_pif_admin', 
            array($this,'wpfy_product_inquiry_settings'), 
            'dashicons-media-text', 
            81 
            );
        }
    
        public function wpfy_product_inquiry_settings(){
            echo 'This is a text';
        }



    }

}