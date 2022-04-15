<?php

// [product_attribute_mixed]

$attributes_tax = wc_get_attribute_taxonomies();
$attributes = array();
foreach ( $attributes_tax as $attribute ) {
	$attributes[ $attribute->attribute_label ] = $attribute->attribute_name;
}

vc_map(array(
   "name" 			=> esc_html__('Products by Attribute', 'woodstock'),
   "category" 		=> 'WooCommerce',
   "description"	=> "",
   "base" 			=> "product_attribute_mixed",
   "class" 			=> "",
   "icon" 			=> "product_attribute_mixed",
   
   "params" 	=> array(

		array(
			"type"			=> "textfield",
			"holder"		=> "div",
			"class" 		=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> esc_html__('Title', 'woodstock'),
			"description"	=> "",
			"param_name"	=> "title",
		),

		array(
			'type' => 'dropdown',
			'heading' => __( 'Attribute', 'js_composer' ),
			'param_name' => 'attribute',
			'value' => $attributes,
			'save_always' => true,
			'description' => __( 'List of product taxonomy attribute', 'js_composer' ),
		),		
      
		array(
			'type' => 'checkbox',
			'heading' => __( 'Filter', 'js_composer' ),
			'param_name' => 'filter',
			'value' => array( 'empty' => 'empty' ),
			'save_always' => true,
			'description' => __( 'Taxonomy values', 'js_composer' ),
			'dependency' => array(
				'callback' => 'vcWoocommerceProductAttributeFilterDependencyCallback',
			),
		),
		
		array(
			"type"			=> "textfield",
			"holder"		=> "div",
			"class" 		=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> esc_html__('Number of Products', 'woodstock'),
			"description"	=> "",
			"param_name"	=> "per_page",
			"value"			=> "4",
		),
		
		array(
			"type"			=> "textfield",
			"holder"		=> "div",
			"class" 		=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> esc_html__('Columns', 'woodstock'),
			"description"	=> "",
			"param_name"	=> "columns",
			"value"			=> "4",
		),
		
		array(
			"type"			=> "dropdown",
			"holder"		=> "div",
			"class" 		=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> esc_html__('Layout Style', 'woodstock'),
			"param_name"	=> "layout",
			"value"			=> array(
				"Listing"	=> "listing",
				"Slider"	=> "slider"
			),
		),
		
		array(
			"type"			=> "dropdown",
			"holder"		=> "div",
			"class" 		=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> "Order By",
			"description"	=> "",
			"param_name"	=> "orderby",
			"value"			=> array(
				__( 'Date', 'js_composer' ) => 'date',
				__( 'ID', 'js_composer' ) => 'ID',
				__( 'Author', 'js_composer' ) => 'author',
				__( 'Title', 'js_composer' ) => 'title',
				__( 'Modified', 'js_composer' ) => 'modified',
				__( 'Random', 'js_composer' ) => 'rand',
				__( 'Comment count', 'js_composer' ) => 'comment_count',
				__( 'Menu order', 'js_composer' ) => 'menu_order',
			),
		),
		
		array(
			"type"			=> "dropdown",
			"holder"		=> "div",
			"class" 		=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> "Order",
			"description"	=> "",
			"param_name"	=> "order",
			"value"			=> array(
				__( 'Descending', 'js_composer' ) => 'DESC',
				__( 'Ascending', 'js_composer' ) => 'ASC',
			),
		),
   )
   
));