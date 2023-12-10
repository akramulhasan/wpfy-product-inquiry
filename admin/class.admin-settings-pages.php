<?php
if(!class_exists('wpfyAdminSettingsPages')){

    class wpfyAdminSettingsPages {

        public static $options;

        public function __construct(){

            self::$options = get_option( 'wpfy_pi_options' );
            add_action('admin_init', array($this, 'admin_init'));
        }


        public function admin_init(){

            register_setting( 'wpfy_pi_group', 'wpfy_pi_options' );

            add_settings_section( 
                'wpfy_pi_main_section', 
                'How does it work?', 
                null, 
                'wpfy_pi_page1' 
            );

            add_settings_field( 
                'wpfy_pi_shortcode', 
                'Shortcode', 
                array($this, 'shortcode_callback'),
                'wpfy_pi_page1', 
                'wpfy_pi_main_section'
            );
            
        }


        /**
         * 
         * Shortcode Field callback description
         * 
         */

         public function shortcode_callback(){
            ?>
                <span>Here will have some descirption of the field</span>
            <?php
         }
    }

}