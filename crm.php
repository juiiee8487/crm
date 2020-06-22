<?php
/*
Plugin Name: CRM System
Plugin URI: https://github.com/juiiee8487/crm.git
Description: This is simple CRM system that will collect customer data and build customer profiles inside of the client’s WordPress Dashboard. They need to collect data from potential customers via a simple lead gen form and then have a list of customers that is easy to browse and keep track of.They want to be able to place this form anywhere via shortcode.
Author: Juhi Patel
Version: 1.0
Author URI: https://juhipatel.me/
License: GPL2
*/

/** 
 * Absolute path to the WordPress directory. 
 */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

/**
 * Custom Function additions.
 */
include 'custom_functions.php';
include 'crm_settings.php';

function crm_flush_rewrites() {
    customer_post_type();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'crm_flush_rewrites' );

register_uninstall_hook( __FILE__, 'crm_plugin_uninstall' );
function crm_plugin_uninstall() {
    unregister_post_type( 'customers' );
}
function crm_enqueue_script() {   
    wp_enqueue_script( 'crm_custom_js', plugins_url( '/assets/js/custom.js',__FILE__ ),array('jquery'), '', true );
    wp_enqueue_style( 'crm_style', plugins_url( '/assets/css/crm_style.css',__FILE__ ) );
    wp_localize_script( 'crm_custom_js', 'customer_ajax_url', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('wp_enqueue_scripts', 'crm_enqueue_script');

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'crm_add_plugin_page_settings_link');
function crm_add_plugin_page_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( 'options-general.php?page=crm-setting-page' ) .
		'">' . __('Settings') . '</a>';
	return $links;
}

