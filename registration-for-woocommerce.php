<?php
/**
 * Plugin Name: Registration For WooCommerce
 * Description: Extended registration form for woocommerce
 * Version: 1.0.0
 * Author: Salive
 * Author URI:
 * Text Domain: registration-for-woocommerce
 * Domain Path: /languages/
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define WR_PLUGIN_FILE.
if ( ! defined( 'WCR_PLUGIN_FILE' ) ) {
	define( 'WCR_PLUGIN_FILE', __FILE__ );
}

// Include the main Registration_for_woocommerce class.
if ( ! class_exists( 'Registration_for_woocommerce' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-registration-for-woocommerce.php';
}

// Initialize the plugin.
add_action( 'plugins_loaded', array( 'Registration_for_woocommerce', 'get_instance' ) );
