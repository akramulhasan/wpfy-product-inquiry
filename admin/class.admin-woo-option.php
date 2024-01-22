<?php
if (!class_exists('wpfyAdminWooOption')) {
    class wpfyAdminWooOption
    {
        public function __construct()
        {   // Filter to create a custom option tab
            add_filter('woocommerce_product_data_tabs', array($this, 'woo_inquiry_tab'));

            // Filter to add field to the tab
            add_action('woocommerce_product_data_panels', array($this, 'woo_inquiry_tab_data'));

            // Fiter to save field data
            add_action('woocommerce_process_product_meta', array($this, 'woo_inquiry_tab_data_saving'));
        }

        /**
         * Custom Option Tab for Inquiry in Woocommerce data tab
         */

        public function woo_inquiry_tab($tabs)
        {
            $tabs['inquiry_option'] = [
                'label' => __('Inquiry Option', 'wpfypi'),
                'target' => 'inquiry_option_data',
                'class' => ['hide_if_external'],
                'priority' => 150
            ];

            return $tabs;
        }

        /**
         * Custom Option Tab data
         */

        public function woo_inquiry_tab_data()
        { ?>
            <div id="inquiry_option_data" class="panel woocommerce_options_panel hidden">
                <?php


                woocommerce_wp_checkbox([
                    'id' => '_inquiry_option_checkbox',
                    'label' => __('Enable Inquiry', 'wpfypi'),
                ]);

                ?></div><?php
                    }




                    public function woo_inquiry_tab_data_saving($post_id)
                    {
                        $product = wc_get_product($post_id);
                        $inquiry_option_checkbox = isset($_POST['_inquiry_option_checkbox']) ? 'yes' : '';
                        $product->update_meta_data('_inquiry_option_checkbox', $inquiry_option_checkbox);

                        $product->save();
                    }
                } // End Class
            }
