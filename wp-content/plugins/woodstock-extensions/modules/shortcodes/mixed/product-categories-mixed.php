<?php

// [product_category_mixed]
function shortcode_product_categories_mixed($atts, $content = null) {

	global $woocommerce_loop;

	extract(shortcode_atts(array(
		'title' => '',
		'number'     => null,
		'columns'    => '4',
		'orderby'    => 'name',
		'order'      => 'ASC',		
		'layout'  => 'listing',
		'hide_empty' => 'yes',
		'parent'     => '',
		'ids'        => ''		
	), $atts));

	extract( $atts );
	

	if ( isset( $ids ) ) {
		$ids = explode( ',', $ids );
		$ids = array_map( 'trim', $ids );
	} else {
		$ids = array();
	}

	$hide_empty = ( $hide_empty == 'yes' || $hide_empty == 1 ) ? 1 : 0;

	// get terms and workaround WP bug with parents/pad counts
	$args = array(
		'orderby'    => $orderby,
		'order'      => $order,
		'hide_empty' => $hide_empty,
		'include'    => $ids,
		'pad_counts' => true,
		'child_of'   => $parent
	);

	$product_categories = get_terms( 'product_cat', $args );

	if ( '' !== $parent ) {
		$product_categories = wp_list_filter( $product_categories, array( 'parent' => $parent ) );
	}	

	if ( $hide_empty ) {
		foreach ( $product_categories as $key => $category ) {
			if ( $category->count == 0 ) {
				unset( $product_categories[ $key ] );
			}
		}
	}

	if ( $number ) {
		$product_categories = array_slice( $product_categories, 0, $number );
	}

	$columns = absint( $columns );

	if ( WC()->version < '3.3.0' ){
		$woocommerce_loop['columns'] = $columns;
	}else{
		wc_set_loop_prop( 'columns', $columns );
	}	

	ob_start();

	$woocommerce_loop['loop'] = '';

	if ( $product_categories ) {

		if ($title != '') {
			echo '<h2 class="shortcode_title">' . $title . '</h2>';
		}		

		if ($layout == "listing") {



			$categories_per_column = $columns;

			if ($categories_per_column == 7) {
			    $categories_per_column_xlarge = 7;
			    $categories_per_column_large = 7;
			    $categories_per_column_medium = 3;
			    $categories_per_column_small = 2;
			}			

			if ($categories_per_column == 6) {
			    $categories_per_column_xlarge = 6;
			    $categories_per_column_large = 6;
			    $categories_per_column_medium = 4;
			    $categories_per_column_small = 2;
			}

			if ($categories_per_column == 5) {
			    $categories_per_column_xlarge = 5;
			    $categories_per_column_large = 5;
			    $categories_per_column_medium = 3;
			    $categories_per_column_small = 2;
			}

			if ($categories_per_column == 4) {
			    $categories_per_column_xlarge = 4;
			    $categories_per_column_large = 4;
			    $categories_per_column_medium = 3;
			    $categories_per_column_small = 2;
			}

			if ($categories_per_column == 3) {
			    $categories_per_column_xlarge = 3;
			    $categories_per_column_large = 3;
			    $categories_per_column_medium = 2;
			    $categories_per_column_small = 2;
			}

			if ($categories_per_column == 2) {
			    $categories_per_column_xlarge = 2;
			    $categories_per_column_large = 2;
			    $categories_per_column_medium = 2;
			    $categories_per_column_small = 2;
			}

			 
			?>

			<ul id="products" class="product-category-list small-block-grid-1 medium-block-grid-<?php echo esc_attr($categories_per_column_medium); ?> large-block-grid-<?php echo esc_attr($categories_per_column_large); ?> xlarge-block-grid-<?php echo esc_attr($categories_per_column_xlarge); ?> xxlarge-block-grid-<?php echo esc_attr($categories_per_column); ?> columns-<?php echo esc_attr($categories_per_column); ?>">
				<?php 
					foreach ( $product_categories as $category ) {
						wc_get_template( 'content-product_cat.php', array(
							'category' => $category
						) );
					}
				 ?>	
	        </ul> 


		<?php

		} else {

		$sliderrandomid = rand();
		?>
		<div id="products-carousel" class="products-carousels-<?php echo $sliderrandomid ?>">
	       <ul id="products" class="product-category-list owl-carousel owl-theme columns-<?php echo $columns; ?>">
				<?php 
					foreach ( $product_categories as $category ) {
						wc_get_template( 'content-product_cat.php', array(
							'category' => $category
						) );
					}
				 ?>	
	        </ul> 
		</div>

	<script>
    jQuery(document).ready(function($) {

        "use strict";

        var owl = $('.products-carousels-<?php echo $sliderrandomid ?> #products');
        owl.owlCarousel({
            items:<?php echo $columns; ?>,
            margin:20,
            lazyLoad:true,
            dots:false,
            responsiveClass:true,
            nav:true,
            navText: [
                "",
                ""
            ],
            responsive:{
                0:{
                    items:<?php if ($columns == 1) {
                        echo '1';
                    } else {
                        echo '2';
                    }; ?>,
                    nav:false,
                    dots:true,
                },
                600:{
                    items:<?php if ($columns == 1) {
                        echo '1';
                    } else {
                        echo '3';
                    }; ?>,
                    nav:false,
                    dots:true,
                },
                1000:{
                    items:<?php if ($columns == 1) {
                        echo '1';
                    } else {
                        echo '4';
                    }; ?>,
                    nav:true,
                },
                1200:{
                    items:<?php echo $columns; ?>,
                    nav:true,
                }
            }
        });
    
    });
    </script>


		<?php
		}

	}



	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode("product_categories_mixed", "shortcode_product_categories_mixed");