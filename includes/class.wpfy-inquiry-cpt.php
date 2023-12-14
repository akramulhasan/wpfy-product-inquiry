<?php 
if(!class_exists('wpfyInquiryCpt')){
    
    class wpfyInquiryCpt {

        /**
         * Class construct
         */
        public function __construct(){

            add_action('init', array($this, 'wpfypi_register_custom_post_type'));

        }


        /**
         * register_post_type method description
         */

        public function wpfypi_register_custom_post_type(){
            
            $labels = array(
                'name'               => _x('Inquiries', 'post type general name', 'wpfypi'),
                'singular_name'      => _x('Inquiry', 'post type singular name', 'wpfypi'),
                'menu_name'          => _x('Inquiries', 'admin menu', 'wpfypi'),
                'name_admin_bar'     => _x('Inquiry', 'add new on admin bar', 'wpfypi'),
                'add_new'            => _x('Add New', 'inquiry', 'wpfypi'),
                'add_new_item'       => __('Add New Inquiry', 'wpfypi'),
                'new_item'           => __('New Inquiry', 'wpfypi'),
                'edit_item'          => __('Edit Inquiry', 'wpfypi'),
                'view_item'          => __('View Inquiry', 'wpfypi'),
                'all_items'          => __('All Inquiries', 'wpfypi'),
                'search_items'       => __('Search Inquiries', 'wpfypi'),
                'parent_item_colon'  => __('Parent Inquiries:', 'wpfypi'),
                'not_found'          => __('No inquiries found.', 'wpfypi'),
                'not_found_in_trash' => __('No inquiries found in Trash.', 'wpfypi')
            );
        
            $args = array(
                'labels'                => $labels,
                'public'                => false, // It's not intended to be publicly queryable
                'publicly_queryable'    => false, // Should not be publicly queryable
                'show_ui'               => true, // Show in admin UI
                'show_in_menu'          => true, // Show in the admin menu
                'query_var'             => true,
                'rewrite'               => array('slug' => 'inquiry'),
                'capability_type'       => 'post',
                'has_archive'           => false, // No need for archive page
                'hierarchical'          => false,
                'menu_position'         => null,
                'supports'              => array('title', 'editor'), // Assuming you want title and message body
                'show_in_rest'          => true, // If you wish to expose this CPT to the REST API
            );
        
            register_post_type('wpfypi_inquiry', $args);
        }
    }
}

?>