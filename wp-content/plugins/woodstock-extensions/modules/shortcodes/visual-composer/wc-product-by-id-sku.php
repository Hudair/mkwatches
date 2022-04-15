<?php

// [product]


vc_map(array(
   "name" 			=> "Single Product",
   "category" 		=> 'WooCommerce',
   "description"	=> "",
   "base" 			=> "product_mod",
   "class" 			=> "",
   "icon" 			=> "product",
   
   "params" 	=> array(
		

    array(
        'type'        => 'autocomplete',
        'heading'     => esc_html__( 'Product', 'woodstock' ),
        'param_name'  => 'ids',
        'settings'    => array(
            'multiple' => false,
            'sortable' => false,
        ),
        "admin_label" 	=> true,
        'save_always' => true,
        "description" 	=> esc_html__( "Select the product you'd like to display.", 'woodstock' )
    ),
		
   )
   
));