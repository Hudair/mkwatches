<?php

// [product_categories]

$order_by_values = array(
	'',
	esc_html__( 'Date', 'woodstock' ) => 'date',
	esc_html__( 'ID', 'woodstock' ) => 'ID',
	esc_html__( 'Author', 'woodstock' ) => 'author',
	esc_html__( 'Title', 'woodstock' ) => 'title',
	esc_html__( 'Modified', 'woodstock' ) => 'modified',
	esc_html__( 'Comment count', 'woodstock' ) => 'comment_count',
	esc_html__( 'Menu order', 'woodstock' ) => 'menu_order',
	esc_html__( 'As IDs or slugs provided order', 'woodstock' ) => 'include',
);

$order_way_values = array(
	'',
	esc_html__( 'Descending', 'woodstock' ) => 'DESC',
	esc_html__( 'Ascending', 'woodstock' ) => 'ASC',
);

vc_map(array(
   "name"			=> esc_html__('Product Categories - Thumbs', 'woodstock'),
   "category"		=> 'WooCommerce',
   "description"	=> "",
   "base"			=> "product_categories_mixed",
   "class"			=> "",
   "icon"			=> "product_categories",
   
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
			"type"			=> "textfield",
			"holder"		=> "div",
			"class" 		=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> esc_html__('How many product categories to display?', 'woodstock'),
			"param_name"	=> "number",
			"value"			=> "",
		),
		
		array(
			"type"			=> "textfield",
			"holder"		=> "div",
			"class" 		=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> esc_html__('Columns', 'woodstock'),
			"param_name"	=> "columns",
			"value"			=> "",
		),

		
		array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Order by', 'woodstock' ),
			'param_name' => 'orderby',
			'value' => $order_by_values,
			'save_always' => true,
			'description' => sprintf( wp_kses(  __( 'Select how to sort retrieved categories. More at %s.', 'woodstock' ), array(
                    'a' => array( 
                        'href' => array(), 
                        'target' => array()
                    )
            	)), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
		),
		array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Sort order', 'woodstock' ),
			'param_name' => 'order',
			'value' => $order_way_values,
			'save_always' => true,
			'description' => sprintf( wp_kses(  __( 'Designates the ascending or descending order. More at %s.', 'woodstock' ), array(
                    'a' => array( 
                        'href' => array(), 
                        'target' => array()
                    )
            	)), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
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
			'type' => 'checkbox',
			'heading' => esc_html__( 'Hide empty', 'woodstock' ),
			'param_name' => 'hide_empty',
			'value' => array( esc_html__( 'Yes, please', 'woodstock' ) => 'yes' ),
			'std'         => 'yes',
		),		

		
		// array(
		// 	"type"			=> "textfield",
		// 	"holder"		=> "div",
		// 	"class" 		=> "hide_in_vc_editor",
		// 	"admin_label" 	=> true,
		// 	"heading"		=> esc_html__('IDs', 'woodstock'),
		// 	"description"	=> esc_html__('Set ids to a comma separated list of category ids to only show those.', 'woodstock'),
		// 	"param_name"	=> "ids",
		// 	"value"			=> "",
		// ),

		array(
			'type' => 'autocomplete',
			'heading' => __( 'Categories', 'js_composer' ),
			'param_name' => 'ids',
			'settings' => array(
				'multiple' => true,
				'sortable' => true,
							),
			'save_always' => true,
			'description' => __( 'List of product categories', 'js_composer' ),
		),		
   )
   
));

//Filters For autocomplete param:
//For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
add_filter( 'vc_autocomplete_product_categories_mixed_ids_callback', 'woodstock_productCategoryCategoryAutocompleteSuggester', 10, 1 ); // Get suggestion(find). Must return an array
add_filter( 'vc_autocomplete_product_categories_mixed_ids_render', 'woodstock_productCategoryCategoryRenderByIdExact', 10, 1 );

if( ! function_exists( 'woodstock_productCategoryCategoryAutocompleteSuggester' ) ) {
	function woodstock_productCategoryCategoryAutocompleteSuggester( $query, $slug = false ) {
		global $wpdb;
		$cat_id = (int) $query;
		$query = trim( $query );
		$post_meta_infos = $wpdb->get_results(
			$wpdb->prepare( "SELECT a.term_id AS id, b.name as name, b.slug AS slug
						FROM {$wpdb->term_taxonomy} AS a
						INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
						WHERE a.taxonomy = 'product_cat' AND (a.term_id = '%d' OR b.slug LIKE '%%%s%%' OR b.name LIKE '%%%s%%' )",
				$cat_id > 0 ? $cat_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

		$result = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data = array();
				$data['value'] = $slug ? $value['slug'] : $value['id'];
				$data['label'] = esc_html__( 'Id', 'woodstock' ) . ': ' .
				                 $value['id'] .
				                 ( ( strlen( $value['name'] ) > 0 ) ? ' - ' . esc_html__( 'Name', 'woodstock' ) . ': ' .
				                                                      $value['name'] : '' ) .
				                 ( ( strlen( $value['slug'] ) > 0 ) ? ' - ' . esc_html__( 'Slug', 'woodstock' ) . ': ' .
				                                                      $value['slug'] : '' );
				$result[] = $data;
			}
		}

		return $result;
	}
}
if( ! function_exists( 'woodstock_productCategoryCategoryRenderByIdExact' ) ) {
	function woodstock_productCategoryCategoryRenderByIdExact( $query ) {
		global $wpdb;
		$query = $query['value'];
		$cat_id = (int) $query;
		$term = get_term( $cat_id, 'product_cat' );

		return woodstock_productCategoryTermOutput( $term );
	}
}

if( ! function_exists( 'woodstock_productCategoryTermOutput' ) ) {
	function woodstock_productCategoryTermOutput( $term ) {
		$term_slug = $term->slug;
		$term_title = $term->name;
		$term_id = $term->term_id;

		$term_slug_display = '';
		if ( ! empty( $term_sku ) ) {
			$term_slug_display = ' - ' . esc_html__( 'Sku', 'woodstock' ) . ': ' . $term_slug;
		}

		$term_title_display = '';
		if ( ! empty( $product_title ) ) {
			$term_title_display = ' - ' . esc_html__( 'Title', 'woodstock' ) . ': ' . $term_title;
		}

		$term_id_display = esc_html__( 'Id', 'woodstock' ) . ': ' . $term_id;

		$data = array();
		$data['value'] = $term_id;
		$data['label'] = $term_id_display . $term_title_display . $term_slug_display;

		return ! empty( $data ) ? $data : false;
	}
}