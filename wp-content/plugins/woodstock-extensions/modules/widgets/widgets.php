<?php


/**
 * Register widgets
 *
 * @since  1.0
 *
 * @return void
 */


function woodstock_addons_register_widgets() {

	if ( class_exists( 'WC_Widget' ) ) {
		require_once WOODSTOCK_ADDONS_DIR . '/modules/widgets/woo-attributes-filter.php';
		require_once WOODSTOCK_ADDONS_DIR . '/modules/widgets/woocommerce-cart.php';
		require_once WOODSTOCK_ADDONS_DIR . '/modules/widgets/product-categories.php';

		register_widget( 'Woodstock_Widget_Attributes_Filter' );
		register_widget( 'TDL_WC_Widget_Cart' );
		register_widget( 'Woodstock_Product_Categories_Widget' );
	}
}

add_action( 'widgets_init', 'woodstock_addons_register_widgets' );