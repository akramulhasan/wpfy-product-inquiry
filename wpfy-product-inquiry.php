<?php
/**
 ** WPFY Product Inquiry
 *
 * @package           wpfypi
 * @author            wpfy
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: WPFY Product Inquiry
 * Description: Allow users to inquire about products on your WooCommerce store.
 * Version: 1.0
 * Author: wpfy
 * Author URI: https://www.akramulhasan.com
 * Plugin URI: https://www.akramulhasan.com/wpfy-product-inquiry
 * Text Domain: wpfypi
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * WC requires at least: 3.0.0
 * WC tested up to: 5.9.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class WPFY_Product_Inquiry {

    /**
     * Class constructor.
     */
    public function __construct() {

        /**
         * Check if WooCommerce is active
         */
        if ($this->is_woocommerce_active()) {
            
            /**
             * Call Constants method
             */
            $this->define_constants();


            // Proceed with the activation
            register_activation_hook( __FILE__, array( 'WPFY_Product_Inquiry', 'activate' ) );
            register_deactivation_hook( __FILE__, array( 'WPFY_Product_Inquiry', 'deactivate' ) );
            register_uninstall_hook( __FILE__, array( 'WPFY_Product_Inquiry', 'uninstall' ) );

            /**
             * Declare compatible with HPOS new order table 
             */

             add_action('before_woocommerce_init', array($this, 'hpos_compatibility'));


            //Include the wpfyInnquiryForm Class and instantiate
            require_once(WPFY_PY_PATH . '/includes/class.wpfy-inquiry-form.php');
            $wpfy_inquiry_form = new wpfyInquiryForm();

            //Include the wpfyInnquiryForm Class and instantiate
            require_once(WPFY_PY_PATH . '/includes/class.wpfy-inquiry-api.php');
            $wpfy_inquiry_api = new wpfyInquiryApi();

            //Include the wpfyAdminMenu Class and instantiate
            require_once(WPFY_PY_PATH . '/admin/class.admin-menu.php');
            $wpfy_admin_menu = new wpfyAdminMenu();

            //Include the wpfyAdminSettingsPages Class and instantiate
            require_once(WPFY_PY_PATH . '/admin/class.admin-settings-pages.php');
            $wpfy_admin_settings_pages = new wpfyAdminSettingsPages();

        } else {
            // Display a notice if WooCommerce is not active
            add_action('admin_notices', array($this, 'woocommerce_not_active_notice'));
           
            // Deactivate the plugin
            add_action('admin_init', array($this, 'deactivate_plugin'));
        }
    }

    /**
     * Check if WooCommerce is active
     */
    private function is_woocommerce_active() {
        return in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));
    }


    /**
    * Call Constants method
    */
    function define_constants(){
        define( 'WPFY_PY_PATH', plugin_dir_path( __FILE__ ) );
        define( 'WPFY_PY_URL', plugin_dir_url( __FILE__ ) );
        define( 'WPFY_PY_VERSION', '1.0.0' );
    }

    /**
     * Display notice if WooCommerce is not active
     */
    public function woocommerce_not_active_notice() {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('WPFY Product Inquiry requires WooCommerce to be active. Please activate WooCommerce to use this plugin.', 'wpfypi'); ?></p>
        </div>
        <?php
    }

    /**
     * Deactivate the plugin
     */
    public function deactivate_plugin() {
        deactivate_plugins(plugin_basename(__FILE__));
        return;
    }

    /**
     * Declare compatible with HPOS new order table 
     */
    public function hpos_compatibility() {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    }

    /**
     * Static methods for Activate, Deactivate, Uninstall
     */
    public static function activate(){
        update_option('rewrite_rules', '');
    }

    public static function deactivate(){
        flush_rewrite_rules();
    }
    
    public static function uninstall(){

    }
}

/**
* Instantiate the plugin class.
*/

if( class_exists( 'WPFY_Product_Inquiry' ) ){

    $wpfy_slider = new WPFY_Product_Inquiry();
}