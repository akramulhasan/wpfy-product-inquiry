<?php
if (!class_exists('wpfyAdminMenu')) {

    class wpfyAdminMenu
    {

        public function __construct()
        {
            add_action('admin_menu', array($this, 'add_menu'));

            // Enqueue scripts and styles
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts_styles'));
        }

        public function add_menu()
        {
            add_menu_page(
                'WPFY Product Inquiry Settings',
                'Product Inquiry',
                'manage_options',
                'wpfy_pif_admin',
                array($this, 'wpfy_product_inquiry_settings'),
                'dashicons-media-text',
                81
            );

            // Admin Menu for holding a page to show inquiry details ( this will be hidden from the menu)
            add_menu_page(
                'Inquiry Details',
                'Inquiry Details',
                'manage_options',
                'inquiry-details',
                array($this, 'render_inquiry_details_page'),
                null,
                null
            );
        }

        public function render_inquiry_details_page()
        {
            // Fetch inquiry ID from the URL parameter
            $inquiryId = isset($_GET['inquiry_id']) ? absint($_GET['inquiry_id']) : 0;

            // Fetch detailed inquiry information
            $inquiryDetails = get_post($inquiryId);

            // Display the details as needed
?>
            <div class="wrap inquiry-details-wrap">
                <h1>Inquiry Details</h1>
                <div class="inquiry-details">
                    <p><strong>Name:</strong> <?php echo esc_html($inquiryDetails->post_title); ?></p>
                    <p><strong>Email:</strong> <?php echo esc_html(get_post_meta($inquiryDetails->ID, 'wpfypi_email', true)); ?></p>
                    <p><strong>Message:</strong> <?php echo esc_html($inquiryDetails->post_content); ?></p>
                    <p><strong>Product:</strong> <?php echo esc_html(get_post_meta($inquiryDetails->ID, 'wpfypi_product_name', true)); ?></p>

                    <button class="button send-email-reply">Send Email Reply</button>
                </div>

                <div class="email-composer-section">
                    <h2>Email Composer</h2>
                    <form id="email-composer" method="post" action="">
                        <?php //wp_nonce_field('compose_email_nonce', 'compose_email_nonce');
                        ?>
                        <label for="email_body">Email Body:</label>
                        <?php
                        $settings = array(
                            'textarea_name' => 'email_body',
                            'editor_class' => 'email-body-editor',
                        );
                        wp_editor('', 'email_body', $settings);
                        ?>
                        <input type="submit" class="button email-submit" data-nonce="<?php echo wp_create_nonce('compose_email_nonce'); ?>" data-inquiry-id="<?php echo $inquiryDetails->ID; ?>" value="Send Email">
                    </form>
                </div>
            </div>


<?php
        }

        public function wpfy_product_inquiry_settings()
        {

            //Include settings-page file if user has proper role
            if (!current_user_can('manage_options')) {
                return;
            }

            if (isset($_GET['settings-updated'])) {
                add_settings_error('wpfy_pi_options', 'wpfy_pi_message', 'Settings Saved', 'success');
            }

            settings_errors('wpfy_pi_options');

            require_once(WPFY_PY_PATH . 'admin/settings-page.php');
        }

        /**
         * Enqueue Scripts and Styles Method
         */
        public function enqueue_scripts_styles()
        {
            wp_enqueue_style('wpfy-inquiry-css', WPFY_PY_URL . 'admin/assets/inquiry.css');
            wp_enqueue_script('wpfy-inquiry-popup-js', WPFY_PY_URL . 'admin/assets/inquiry.js', array('jquery'), '1.0.0', true);
            wp_localize_script('wpfy-inquiry-popup-js', 'ajax_object', array('nonce' => wp_create_nonce('get_inquiry_details_nonce')));
        }
    }
}
