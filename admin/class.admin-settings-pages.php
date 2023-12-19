<?php
if(!class_exists('wpfyAdminSettingsPages')){

    class wpfyAdminSettingsPages {

        public static $options;

        public function __construct(){

            self::$options = get_option( 'wpfy_pi_options' );
            add_action('admin_init', array($this, 'admin_init'));

            // Add AJAX action hooks for details view

            add_action('wp_ajax_get_inquiry_details', array($this, 'get_inquiry_details'));
            add_action('wp_ajax_nopriv_get_inquiry_details', array($this, 'get_inquiry_details'));
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
         * Callback description of render_submission_table-1
         * 
         */

         public function render_submission_table (){
                    // Get the current ordering/order parameters
                    $orderby = isset($_GET['orderby']) ? $_GET['orderby'] : 'id';
                    $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

                    // Set the number of inquiries per page
                    $per_page = 10;

                    // Get the current page number
                    $paged = isset($_GET['paged']) ? absint($_GET['paged']) : 1;

                    // Calculate the offset based on the current page and inquiries per page
                    $offset = ($paged - 1) * $per_page;

                    // Get inquiries from the custom post type 'wpfypi_inquiry' with pagination
                    $args = array(
                        'post_type' => 'wpfypi_inquiry',
                        'posts_per_page' => $per_page,
                        'post_status' => 'any',
                        'orderby' => $orderby,
                        'order' => $order,
                        'offset' => $offset,
                    );
                    $inquiries = get_posts($args);
                    ?>

                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>
                                    <a href="<?php echo add_query_arg(array('orderby' => 'id', 'order' => $order)); ?>">
                                        <?php _e('ID', 'wpfypi'); ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="<?php echo add_query_arg(array('orderby' => 'title', 'order' => $order)); ?>">
                                        <?php _e('Name', 'wpfypi'); ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="<?php echo add_query_arg(array('orderby' => 'meta_value', 'meta_key' => 'wpfypi_email', 'order' => $order)); ?>">
                                        <?php _e('Email', 'wpfypi'); ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="<?php echo add_query_arg(array('orderby' => 'meta_value', 'meta_key' => 'wpfypi_product_name', 'order' => $order)); ?>">
                                        <?php _e('Product', 'wpfypi'); ?>
                                    </a>
                                </th>
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
                                <td><?php echo get_post_meta($inquiry->ID, 'wpfypi_product_name', true); ?></td>
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
                    <div id="inquiry-details"></div> <!-- Will require some styling to position this element appropriately -->

                    <?php
                    // Get the total number of inquiries for pagination
                    $total_inquiries = wp_count_posts('wpfypi_inquiry')->publish;

                    // Calculate the total number of pages based on the total inquiries and inquiries per page
                    $total_pages = ceil($total_inquiries / $per_page);

                    // Get the current page URL without the paged parameter
                    $current_url = remove_query_arg('paged');

                    // Generate the pagination links with the correct query parameters
                    $pagination_links = paginate_links(array(
                        'base' => $current_url . '%_%',
                        'format' => '&paged=%#%',
                        'prev_text' => __('&laquo; Previous', 'wpfypi'),
                        'next_text' => __('Next &raquo;', 'wpfypi'),
                        'total' => $total_pages,
                        'current' => $paged,
                    ));

                    echo '<div class="tablenav">' . $pagination_links . '</div>';
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

          /**
           * Description of get_inquiry_details method
           */

          public function get_inquiry_details() {
            // Verify nonce for security
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'get_inquiry_details_nonce')) {
                wp_send_json_error('Invalid nonce');
            }
        
            $inquiryId = isset($_POST['inquiry_id']) ? absint($_POST['inquiry_id']) : 0;
        
            // Fetch detailed inquiry information
            $inquiryDetails = get_post($inquiryId);
        
            // You can customize the HTML structure based on your detailed inquiry information
            $html = '<p>ID: ' . $inquiryDetails->ID . '</p>';
            $html .= '<p>Name: ' . $inquiryDetails->post_title . '</p>';
            // Add more details...
        
            wp_send_json_success($html);
            wp_die();
        }
    }

}