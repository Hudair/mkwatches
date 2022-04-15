<?php global $post, $product, $woocommerce; ?>

    <?php 
        /* get swatch html */
        add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'eva_get_swatch_html', 10, 2 );
        function eva_get_swatch_html( $html, $args ) {

            /* return `$html` when `WooCommerce Variation Swatches` plugin not installed */
            if(!function_exists('TA_WCVS')) return $html;

            $swatch_types = TA_WCVS()->types;
            $attr         = TA_WCVS()->get_tax_attribute( $args['attribute'] );
    
            /* Return if this is normal attribute */
            if ( empty( $attr ) ) {
                return $html;
            }
    
            if ( ! array_key_exists( $attr->attribute_type, $swatch_types ) ) {
                return $html;
            }
    
            $options   = $args['options'];
            $product   = $args['product'];
            $attribute = $args['attribute'];
            $class     = "variation-selector variation-select-{$attr->attribute_type}";
            $swatches  = '';
    
            if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
                $attributes = $product->get_variation_attributes();
                $options    = $attributes[$attribute];
            }

            if ( array_key_exists( $attr->attribute_type, $swatch_types ) ) {
                if ( ! empty( $options ) && $product && taxonomy_exists( $attribute ) ) {
                    /* Get terms if this is a taxonomy - ordered. We need the names too. */
                    $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
    
                    foreach ( $terms as $term ) {
                        if ( in_array( $term->slug, $options ) ) {
                            $swatches .= apply_filters( 'tawcvs_swatch_html', '', $term, $attr, $args );
                        }
                    }
                }
    
                if ( ! empty( $swatches ) ) {
                    $class .= ' hidden';
    
                    $swatches = '<div class="tawcvs-swatches" data-attribute_name="attribute_' . esc_attr( $attribute ) . '">' . $swatches . '</div>';
                    $html     = '<div class="' . esc_attr( $class ) . '">' . $html . '</div>' . $swatches;
                }
            }
    
            return $html;
        }

        /* print html watch */
        add_filter( 'tawcvs_swatch_html', 'eva_swatch_html', 5, 4 );
        function eva_swatch_html( $html, $term, $attr, $args ) {

            /* return `$html` when `WooCommerce Variation Swatches` plugin not installed */
            if(!function_exists('TA_WCVS')) return $html;

            $selected = sanitize_title( $args['selected'] ) == $term->slug ? 'selected' : '';
            $name     = esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) );
    
            switch ( $attr->attribute_type ) {
                case 'color':
                    $color = get_term_meta( $term->term_id, 'color', true );
                    list( $r, $g, $b ) = sscanf( $color, "#%02x%02x%02x" );
                    $html = sprintf(
                        '<span class="swatch swatch-color swatch-%s %s" style="background-color:%s;color:%s;" title="%s" data-value="%s">%s</span>',
                        esc_attr( $term->slug ),
                        $selected,
                        esc_attr( $color ),
                        "rgba($r,$g,$b,0.5)",
                        esc_attr( $name ),
                        esc_attr( $term->slug ),
                        $name
                    );
                    break;
    
                case 'image':
                    $image = get_term_meta( $term->term_id, 'image', true );
                    $image = $image ? wp_get_attachment_image_src( $image ) : '';
                    $image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
                    $html  = sprintf(
                        '<span class="swatch swatch-image swatch-%s %s" title="%s" data-value="%s"><img src="%s" alt="%s">%s</span>',
                        esc_attr( $term->slug ),
                        $selected,
                        esc_attr( $name ),
                        esc_attr( $term->slug ),
                        esc_url( $image ),
                        esc_attr( $name ),
                        esc_attr( $name )
                    );
                    break;
    
                case 'label':
                    $label = get_term_meta( $term->term_id, 'label', true );
                    $label = $label ? $label : $name;
                    $html  = sprintf(
                        '<span class="swatch swatch-label swatch-%s %s" title="%s" data-value="%s">%s</span>',
                        esc_attr( $term->slug ),
                        $selected,
                        esc_attr( $name ),
                        esc_attr( $term->slug ),
                        esc_html( $label )
                    );
                    break;
            }
    
            return $html;
        }

     ?>

<?php if($product->is_type('grouped') ) { ?>

    <a href="<?php echo get_the_permalink( $product->get_id() ); ?>" rel="nofollow" class="button  product_type_grouped"><?php _e('View products','woocommerce'); ?></a>

<?php } else { ?>

    <?php do_action($this->slug.'-before-addtocart'); ?>

    <?php do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart'  ); ?>

    <?php do_action($this->slug.'-after-addtocart'); ?>

    <?php

    wc_get_template( 'single-product/add-to-cart/variation.php' );

    $wc_add_to_cart = array(
        'ajax_url'                => WC()->ajax_url(),
        'wc_ajax_url'             => WC_AJAX::get_endpoint( "%%endpoint%%" ),
        'i18n_view_cart'          => esc_attr__( 'View Cart', 'woocommerce' ),
        'cart_url'                => apply_filters( 'woocommerce_add_to_cart_redirect', apply_filters( 'woocommerce_get_cart_url', wc_get_page_permalink( 'cart' ) ) ),
        'is_cart'                 => is_cart(),
        'cart_redirect_after_add' => get_option( 'woocommerce_cart_redirect_after_add' )
    );

    $wc_add_to_cart_variation = array(
        'i18n_no_matching_variations_text' => esc_attr__( 'Sorry, no products matched your selection. Please choose a different combination.', 'woocommerce' ),
        'i18n_make_a_selection_text'       => esc_attr__( 'Select product options before adding this product to your cart.', 'woocommerce' ),
        'i18n_unavailable_text'            => esc_attr__( 'Sorry, this product is unavailable. Please choose a different combination.', 'woocommerce' )
    );

    $includes_url         = includes_url();
    $suffix               = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
    $lightbox_en          = 'yes' === get_option( 'woocommerce_enable_lightbox' );
    $ajax_cart_en         = 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' );
    $assets_path          = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';
    $frontend_script_path = $assets_path . 'js/frontend/';

    $add_to_cart_variation_script_id = $this->slug."-add-to-cart-variation";
    $wp_util_script_id = $this->slug."-wp-util";
    $underscore_script_id = $this->slug."-underscore";
    ?>

    <script type="text/javascript">

    jQuery("#jckqv table.variations td select").wrap( '<label class="variation-select"></label>' );

        var wc_add_to_cart_params = <?php echo json_encode($wc_add_to_cart); ?>,
            wc_add_to_cart_variation_params = <?php echo json_encode($wc_add_to_cart_variation); ?>;

        // add +/- buttons to qty

        jQuery( '#jckqv div.quantity:not(.buttons_added), #jckqv td.quantity:not(.buttons_added)' ).addClass( 'buttons_added' ).append( '<div class="<?php echo $this->slug; ?>-qty-spinners"><input type="button" value="+" data-dir="plus" class="<?php echo $this->slug; ?>-qty-spinner <?php echo $this->slug; ?>-qty-spinners__plus" /><input type="button" value="-" data-dir="minus" class="<?php echo $this->slug; ?>-qty-spinner <?php echo $this->slug; ?>-qty-spinners__minus" /></div>' );


        // remove script from previous modal

        jQuery('#<?php echo $underscore_script_id; ?>').remove();
        jQuery('#<?php echo $wp_util_script_id; ?>').remove();
        jQuery('#<?php echo $add_to_cart_variation_script_id; ?>').remove();

        // add script again
        // needs to be added every time of it doesn't work effectively

        // Underscore

        if(typeof _ !== 'function') {

            var underscore_script    = document.createElement("script");
            underscore_script.type   = "text/javascript";
            underscore_script.src    = "<?php echo $includes_url . '/js/underscore.min.js'; ?>";
            underscore_script.id     = "<?php echo $underscore_script_id; ?>";

            jQuery("head").append(underscore_script);

        }

        // wp-util

        if(typeof wp.template !== 'function') {

            var wp_util_script    = document.createElement("script");
            wp_util_script.type   = "text/javascript";
            wp_util_script.src    = "<?php echo $includes_url . '/js/wp-util' . $suffix . '.js'; ?>";
            wp_util_script.id     = "<?php echo $wp_util_script_id; ?>";

            jQuery("head").append(wp_util_script);

        }

        // add-to-cart-variation

        var add_to_cart_variation_script    = document.createElement("script");
        add_to_cart_variation_script.type   = "text/javascript";
        add_to_cart_variation_script.src    = "<?php echo $frontend_script_path . 'add-to-cart-variation' . $suffix . '.js'; ?>";
        add_to_cart_variation_script.id     = "<?php echo $add_to_cart_variation_script_id; ?>";

        jQuery("head").append(add_to_cart_variation_script);

        <?php if( $product->is_type('variable') ) { ?>

            <?php $available_variations = $product->get_available_variations() ?>

            var jck_available_variations = <?php echo json_encode($available_variations); ?>;

        <?php } ?>

    </script>

<?php } ?>