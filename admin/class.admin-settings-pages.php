<?php
if(!class_exists('wpfyAdminSettingsPages')){

    class wpfyAdminSettingsPages {

        public static $options;

        public function __construct(){

            self::$options = get_option( 'wpfy_pi_options' );
            add_action('admin_init', array($this, 'admin_init'));
        }


        public function admin_init(){
            
        }
    }

}