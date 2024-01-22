<?php
if (!class_exists('wpfyAdminWooOption')) {
    class wpfyAdminWooOption
    {
        public function __construct()
        {
            add_filter('woocommerce_product_data_tabs', array($this, 'woo_inquiry_tab'));
        }

        /**
         * Custom Option Tab for Inquiry in Woocommerce data tab
         */

        public function woo_inquiry_tab($tabs)
        {
            $tabs['inquiry_option'] = [
                'label' => __('Inquiry Option', 'wpfypi'),
                'target' => 'additional_product_data',
                'class' => ['hide_if_external'],
                'priority' => 150
            ];

            return $tabs;
        }
    }
}
