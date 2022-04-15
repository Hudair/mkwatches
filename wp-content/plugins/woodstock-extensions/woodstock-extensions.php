<?php
/*
Plugin Name: Woodstock Extensions
Plugin URI: http://woodstock.temashdesign.com
Description: Extensions for Woodstock Wordpress Theme. Supplied as a separate plugin so that the customer does not find empty shortcodes on changing the theme.
Version: 2.6
Author: Temash Design
Author URI: http://temashdesign.com/
*/

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'WOODSTOCK_ADDONS_DIR' ) ) {
	define( 'WOODSTOCK_ADDONS_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'WOODSTOCK_ADDONS_URL' ) ) {
	define( 'WOODSTOCK_ADDONS_URL', plugin_dir_url( __FILE__ ) );
}

$theme = wp_get_theme();
if ( $theme->template != 'woodstock') {
	return;
}

global $tdl_plugin_dir;

$tdl_plugin_dir = plugin_dir_path( __FILE__ );

//Load Modules

#-----------------------------------------------------------------
# Theme Shortcodes
#-----------------------------------------------------------------

require_once WOODSTOCK_ADDONS_DIR . '/modules/theme-shortcodes.php';

require_once WOODSTOCK_ADDONS_DIR . '/modules/widgets/widgets.php';



/**
 * Load plugin textdomain.
 *
 * @since 1.2.2
 */
function woodstock_extensions_load_textdomain() {
	load_plugin_textdomain( 'woodstock', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

add_action( 'plugins_loaded', 'woodstock_extensions_load_textdomain' );
