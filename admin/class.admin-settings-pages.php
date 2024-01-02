<?php
if (!class_exists('wpfyAdminSettingsPages')) {

    class wpfyAdminSettingsPages
    {

        public static $options;

        public function __construct()
        {

            self::$options = get_option('wpfy_pi_options');
            add_action('admin_init', array($this, 'admin_init'));

            //add_action('admin_post_send_email_reply', array($this, 'send_email_reply'));


            // Add AJAX action hooks for details view

            add_action('wp_ajax_get_inquiry_details', array($this, 'get_inquiry_details'));
            add_action('wp_ajax_nopriv_get_inquiry_details', array($this, 'get_inquiry_details'));


            // Add AJAX action hooks for compose_email

            add_action('wp_ajax_compose_email', array($this, 'compose_email'));
            add_action('wp_ajax_nopriv_compose_email', array($this, 'compose_email'));
        }

        public function send_email_reply()
        {
            // Check nonce for security
            if (!isset($_POST['compose_email_nonce']) || !wp_verify_nonce($_POST['compose_email_nonce'], 'compose_email_nonce')) {
                wp_die('Invalid nonce');
            }

            // Get the inquiry ID from the query parameters
            $inquiryId = isset($_GET['inquiry_id']) ? absint($_GET['inquiry_id']) : 0;

            // Get the email body from the form submission
            $email_body = isset($_POST['email_body']) ? $_POST['email_body'] : '';

            // Get the email address of the user who submitted the inquiry
            $user_email = get_post_meta($inquiryId, 'wpfypi_email', true);

            // Prepare email subject
            $subject = 'Reply to Your Inquiry';

            // Send the email using wp_mail()
            $sent = wp_mail($user_email, $subject, $email_body);

            // Check if the email was sent successfully
            if ($sent) {
                // Redirect back to the inquiry details page with a success message
                wp_redirect(admin_url('admin.php?page=inquiry-details&inquiry_id=' . $inquiryId . '&email_sent=true'));
            } else {
                // Redirect back to the inquiry details page with an error message
                wp_redirect(admin_url('admin.php?page=inquiry-details&inquiry_id=' . $inquiryId . '&email_sent=false'));
            }

            exit;
        }


        public function admin_init()
        {

            register_setting('wpfy_pi_group', 'wpfy_pi_options', array($this, 'wpfy_pi_validate'));


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

        public function render_submission_table()
        {
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

            // echo '<pre>';
            // print_r($inquiries);
            // echo '</pre>';
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
                    <?php foreach ($inquiries as $inquiry) : ?>
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

        public function title_callback($args)
        {
        ?>
            <input type="text" name="wpfy_pi_options[wpfy_pi_slider_title]" id="wpfy_pi_slider_title" value="<?php echo isset(self::$options['wpfy_pi_slider_title']) ? esc_attr(self::$options['wpfy_pi_slider_title']) : '' ?>">
        <?php
        }

        /**
         * 
         * Title Field callback description
         * 
         */

        public function bullet_callback($args)
        {
        ?>
            <input type="checkbox" name="wpfy_pi_options[wpfy_pi_slider_bullet]" id="wpfy_pi_slider_bullet" value="1" <?php
                                                                                                                        if (isset(self::$options['wpfy_pi_slider_bullet'])) {
                                                                                                                            checked('1', self::$options['wpfy_pi_slider_bullet'], true);
                                                                                                                        }
                                                                                                                        ?>>
        <?php
        }

        /**
         * 
         * Title Field callback description
         * 
         */

        public function style_callback($args)
        {
        ?>
            <select name="wpfy_pi_options[wpfy_pi_slider_style]" id="wpfy_pi_slider_style">

                <?php foreach ($args['items'] as $item) : ?>
                    <option value="<?php echo esc_attr($item) ?>" <?php
                                                                    isset(self::$options['wpfy_pi_slider_style']) ? selected($item, self::$options['wpfy_pi_slider_style'], true) : '';
                                                                    ?>><?php echo esc_html(ucfirst($item)) ?></option>
                <?php endforeach; ?>

            </select>
<?php
        }

        /**
         * Register Settings callback description
         */

        public function wpfy_pi_validate($input)
        {
            $new_input = array();

            foreach ($input as $key => $value) {
                switch ($key) {
                    case 'wpfy_pi_slider_title':
                        if (empty($value)) {
                            add_settings_error('wpfy_pi_options', 'wpfy_pi_message', 'Field cannot be empty', 'error');
                            $value = 'Please type some text';
                        }
                        $new_input[$key] = sanitize_text_field($value);
                        break;

                    case 'wpfy_pi_slider_bullet':
                        $new_input[$key] = sanitize_text_field($value);
                        break;

                    case 'wpfy_pi_slider_style':
                        $new_input[$key] = sanitize_text_field($value);
                        break;

                    default:
                        $new_input[$key] = sanitize_text_field($value);
                        break;
                }
            }

            return $new_input;
        }

        /**
         * Description of get_inquiry_details method
         * This method receives data sent by Ajax from 'View' click
         * This method sent back the 'details_url' with dedicated page slug + inquiry_id
         */

        public function get_inquiry_details()
        {
            // Verify nonce for security
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'get_inquiry_details_nonce')) {
                wp_send_json_error('Invalid nonce');
            }

            $inquiryId = isset($_POST['inquiry_id']) ? absint($_POST['inquiry_id']) : 0;

            // Build the page url where the details of the submission available
            $details_url = add_query_arg(array('page' => 'inquiry-details', 'inquiry_id' => $inquiryId), admin_url('admin.php'));

            wp_send_json_success(array('details_url' => $details_url));

            wp_die();
        }



        /**
         * Description of compose_email method
         * This method receives data sent by Ajax from 'Send Email- [email-submit]' click
         * 
         */

        public function compose_email()
        {
            // Verify nonce for security
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'compose_email_nonce')) {
                wp_send_json_error('Invalid nonce');
            }

            $inquiryId = isset($_POST['inquiry_id']) ? absint($_POST['inquiry_id']) : 0;

            // Retrieve the content of the wp_editor
            $emailBody = isset($_POST['email_body']) ? sanitize_text_field($_POST['email_body']) : '';

            // Get the email address of the user who submitted the inquiry
            $userEmail = get_post_meta($inquiryId, 'wpfypi_email', true);

            // Prepare email subject
            $subject = 'Reply to Your Inquiry';
            $headers = array('From: Me Myself <dev-email@wpengine.local>');
            // Send the email using wp_mail()
            $sent = wp_mail($userEmail, $subject, 'I am fixed', $headers);
            $siteTitle = get_bloginfo();

            // Check if the email was sent successfully
            if ($sent) {
                // Send success response back to the Ajax call
                wp_send_json_success(array('message' => 'Email sent successfully'));
            } else {
                // Send error response back to the Ajax call
                wp_send_json_error('Error sending email');
            }

            // wp_send_json_success(array('details_url' => $emailBody, 'id' => $inquiryId, 'email' => $userEmail, 'siteName' => $siteTitle));

            wp_die();
        } // End compose_email()

    }
}
