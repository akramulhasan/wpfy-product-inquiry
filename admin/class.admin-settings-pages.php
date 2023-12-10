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

            add_settings_section( 
                'wpfy_pi_second_section', 
                'Slider Title', 
                null, 
                'wpfy_pi_page2' 
            );

            add_settings_field( 
                'wpfy_pi_shortcode', 
                'Shortcode', 
                array($this, 'shortcode_callback'),
                'wpfy_pi_page1', 
                'wpfy_pi_main_section'
            );

            // text filed
            add_settings_field( 
                'wpfy_pi_slider_title', 
                'Slider Title', 
                array($this, 'title_callback'),
                'wpfy_pi_page2', 
                'wpfy_pi_second_section'
            );

            // checkbox filed
            add_settings_field( 
                'wpfy_pi_slider_bullet', 
                'Display Bullet', 
                array($this, 'bullet_callback'),
                'wpfy_pi_page2', 
                'wpfy_pi_second_section'
            );

            // select filed
            add_settings_field( 
                'wpfy_pi_slider_style', 
                'Display Style', 
                array($this, 'style_callback'),
                'wpfy_pi_page2', 
                'wpfy_pi_second_section'
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


        /**
         * 
         * Title Field callback description
         * 
         */

         public function title_callback(){
            ?>
                <input 
                type="text" 
                name="wpfy_pi_options[wpfy_pi_slider_title]" 
                id="wpfy_pi_slider_title"
                value="<?php echo isset(self::$options['wpfy_pi_slider_title']) ? esc_attr( self::$options['wpfy_pi_slider_title'] ) : '' ?>"
                >
            <?php
         }

        /**
         * 
         * Title Field callback description
         * 
         */

         public function bullet_callback(){
            ?>
                <input 
                type="checkbox" 
                name="wpfy_pi_options[wpfy_pi_slider_bullet]" 
                id="wpfy_pi_slider_bullet"
                value="1"
                <?php 
                    if(isset(self::$options['wpfy_pi_slider_bullet'])){
                        checked( '1', self::$options['wpfy_pi_slider_bullet'], true );
                    }
                ?>
                >
            <?php
         }

        /**
         * 
         * Title Field callback description
         * 
         */

         public function style_callback(){
            ?>
                <select 
                name="wpfy_pi_options[wpfy_pi_slider_style]" 
                id="wpfy_pi_slider_style">

                <option 
                value="style-1"
                <?php 
                    isset(self::$options['wpfy_pi_slider_style']) ? selected( 'style-1', self::$options['wpfy_pi_slider_style'], true ) : '';
                ?>>Style 1</option>
                <option 
                value="style-2"
                <?php 
                    isset(self::$options['wpfy_pi_slider_style']) ? selected( 'style-2', self::$options['wpfy_pi_slider_style'], true ) : '';
                ?>>Style 2</option>
            
                </select>
            <?php
         }
    }

}