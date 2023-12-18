<?php
if(!class_exists('wpfyAdminSettingsPages')){

    class wpfyAdminSettingsPages {

        public static $options;

        public function __construct(){

            self::$options = get_option( 'wpfy_pi_options' );
            add_action('admin_init', array($this, 'admin_init'));
        }


        public function admin_init(){

            register_setting( 'wpfy_pi_group', 'wpfy_pi_options', array($this, 'wpfy_pi_validate') );


            add_settings_section( 
                'wpfy_pi_main_section', 
                'Here are the list of all submissions', 
                array($this, 'render_submission_table'), 
                'wpfy_pi_page1' 
            );

            add_settings_section( 
                'wpfy_pi_second_section', 
                'Slider Title', 
                null, 
                'wpfy_pi_page2' 
            );


            // text filed
            add_settings_field( 
                'wpfy_pi_slider_title', 
                'Slider Title', 
                array($this, 'title_callback'),
                'wpfy_pi_page2', 
                'wpfy_pi_second_section',
                array(
                    'label_for' => 'wpfy_pi_slider_title'
                )
            );

            // checkbox filed
            add_settings_field( 
                'wpfy_pi_slider_bullet', 
                'Display Bullet', 
                array($this, 'bullet_callback'),
                'wpfy_pi_page2', 
                'wpfy_pi_second_section',
                array(
                    'label_for' => 'wpfy_pi_slider_bullet'
                )
            );

            // select filed
            add_settings_field( 
                'wpfy_pi_slider_style', 
                'Display Style', 
                array($this, 'style_callback'),
                'wpfy_pi_page2', 
                'wpfy_pi_second_section',
                array(
                    'items' => array(
                        'style-1',
                        'style-2'
                        ),
                    'label_for' => 'wpfy_pi_slider_style'
                )
            );
            
        }


        /**
         * 
         * Shortcode Field callback description
         * 
         */

         public function render_submission_table(){
            // Get inquiries from the custom post type 'wpfypi_inquiry'
            $args = array(
                'post_type' => 'wpfypi_inquiry',
                'posts_per_page' => -1,
                'post_status' => 'any'
            );
            $inquiries = get_posts($args);
            // echo '<pre>';
            // print_r($inquiries);
            // echo '</pre>';
            ?>
        
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('ID', 'wpfypi'); ?></th>
                        <th><?php _e('Name', 'wpfypi'); ?></th>
                        <th><?php _e('Email', 'wpfypi'); ?></th>
                        <th><?php _e('Message Excerpt', 'wpfypi'); ?></th>
                        <th><?php _e('Actions', 'wpfypi'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inquiries as $inquiry): ?>
                        <tr>
                            <td><?php echo $inquiry->ID; ?></td>
                            <td><?php echo $inquiry->post_title; ?></td>
                            <td><?php echo get_post_meta($inquiry->ID, 'wpfypi_email', true); ?></td>
                            <td><?php echo wp_trim_words($inquiry->post_content, 10); ?></td>
                            <td>
                                <button class="button view-details" data-inquiry-id="<?php echo $inquiry->ID; ?>">
                                    <?php _e('View', 'wpfypi'); ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        
            <!-- This div will hold the detailed inquiry information -->
            <div id="inquiry-details"></div> <!-- Will some styling to position this element appropriately -->
            
        </div>
        <?php

         }


        /**
         * 
         * Title Field callback description
         * 
         */

         public function title_callback( $args ){
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

         public function bullet_callback( $args ){
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

         public function style_callback( $args ){
            ?>
                <select 
                name="wpfy_pi_options[wpfy_pi_slider_style]" 
                id="wpfy_pi_slider_style">

                <?php foreach ($args['items'] as $item) : ?>
                    <option 
                value="<?php echo esc_attr( $item ) ?>"
                <?php 
                    isset(self::$options['wpfy_pi_slider_style']) ? selected( $item, self::$options['wpfy_pi_slider_style'], true ) : '';
                ?>><?php echo esc_html( ucfirst( $item ) ) ?></option>
                <?php endforeach; ?>
            
                </select>
            <?php
         }

         /**
          * Register Settings callback description
          */

          public function wpfy_pi_validate( $input ){
            $new_input = array();

            foreach ($input as $key => $value) {
                switch ($key) {
                    case 'wpfy_pi_slider_title':
                        if(empty($value)){
                            add_settings_error( 'wpfy_pi_options', 'wpfy_pi_message', 'Field cannot be empty', 'error' );
                            $value = 'Please type some text';
                        }
                        $new_input[$key] = sanitize_text_field( $value );
                        break;

                    case 'wpfy_pi_slider_bullet':
                        $new_input[$key] = sanitize_text_field( $value );
                        break;

                    case 'wpfy_pi_slider_style':
                        $new_input[$key] = sanitize_text_field( $value );
                        break;
                    
                    default:
                        $new_input[$key] = sanitize_text_field( $value );
                        break;
                }
            }

            return $new_input;
          }
    }

}