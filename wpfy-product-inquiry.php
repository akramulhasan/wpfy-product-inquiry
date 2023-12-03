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
         * Call Constants method
         */
        $this->define_constants();

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
    register_activation_hook( __FILE__, array( 'WPFY_Product_Inquiry', 'activate' ) );
    register_deactivation_hook( __FILE__, array( 'WPFY_Product_Inquiry', 'deactivate' ) );
    register_uninstall_hook( __FILE__, array( 'WPFY_Product_Inquiry', 'uninstall' ) );
    $wpfy_slider = new WPFY_Product_Inquiry();
}