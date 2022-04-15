<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_Product_Cat_List_With_Icon_Walker extends Walker {

  public $tree_type = 'product_cat';

  public $db_fields = array(
    'parent' => 'parent',
    'id'     => 'term_id',
    'slug'   => 'slug',
  );

  public function start_lvl( &$output, $depth = 0, $args = array() ) {
    $indent = str_repeat( "\t", $depth );
    $output .= "$indent<ul class='children'".($args['expand_all']==1? 'style=display:block':'').">\n";
  }

  public function end_lvl( &$output, $depth = 0, $args = array() ) {
    $indent = str_repeat( "\t", $depth );
    $output .= "$indent</ul>\n";
  }

  public function start_el( &$output, $cat, $depth = 0, $args = array(), $current_object_id = 0 ) {

    $output .= '<li class="cat-item cat-item-' . $cat->term_id;

    if ( $args['current_category'] == $cat->term_id ) {
      $output .= ' current-cat';
    }

    if ( $args['has_children'] ) {
      $output .= ' cat-parent';
      $drop_icon = "<span class='child-indicator'></span>";
    } else {
      $drop_icon = '';
    }

    if ( $args['current_category_ancestors'] && $args['current_category'] && in_array( $cat->term_id, $args['current_category_ancestors'] ) ) {
      $output .= ' active current-cat-parent';
    }

    if ( $depth == 0 ) {
      $cat_link_classes = 'site-secondary-font';
    } else {
      $cat_link_classes = '';
    }

    if ($args['expand_all'] == 1) {
      $output .= ' active';
    }

    $category_icon= '';
    $category_icon = get_field('tdl_catalog_icon', 'product_cat_'.$cat->term_id);

    if ($category_icon)
      $image = $category_icon['url'];
    else
      $image = wc_placeholder_img_src();

    $category_icon = '<img src="' .  $image . '"  />';

    if ( ! empty( $args['hide_icon'] ) ) {
      $category_icon = '';
    }

    $output .= '"><a class="' . $cat_link_classes . '" href="' . get_term_link( (int) $cat->term_id, $this->tree_type ) . '">' . $category_icon . sprintf(_x( '%s', 'product category name', 'woocommerce' ), '<span>' .$cat->name) . '</span>' . '</a>';

    if ( $args['show_count'] ) {
      $output .= ' <span class="count">(' . $cat->count . ')</span>';
    }

    // $output .= $drop_icon;
  }

  public function end_el( &$output, $cat, $args = array(), $depth = 0 ) {
    $output .= "</li>\n";
  }
  

  public function display_element( $element, &$children_elements, $max_depth, $args, $depth, &$output   ) {
    if ( ! $element || ( 0 === $element->count && ! empty( $args[0]['hide_empty'] ) ) ) {
      return;
    }
    parent::display_element( $element, $children_elements, $max_depth, $args, $depth, $output  );
  }
}

class Woodstock_Product_Categories_Widget extends WC_Widget {

  public $cat_ancestors;
  public $current_cat;

  public function __construct() {
    $this->widget_cssclass    = 'woocommerce widget_product_categories_with_icon';
    $this->widget_description = esc_attr__( 'A list of product categories.', 'barberry' );
    $this->widget_id          = 'woocommerce_product_categories_with_icon';
    $this->widget_name        = esc_attr__( 'Woodstock product categories with icon', 'barberry' );
    $this->settings           = array(
      'orderby' => array(
        'type'  => 'select',
        'std'   => 'name',
        'label' => esc_attr__( 'Order by', 'barberry' ),
        'options' => array(
          'order' => esc_attr__( 'Category order', 'barberry' ),
          'name'  => esc_attr__( 'Name', 'barberry' ),
        ),
      ),
      'count' => array(
        'type'  => 'checkbox',
        'std'   => 1,
        'label' => esc_attr__( 'Show product counts', 'barberry' ),
      ),
      'hide_empty' => array(
        'type'  => 'checkbox',
        'std'   => 0,
        'label' => esc_attr__( 'Hide empty categories', 'barberry' ),
      ),
      'hide_icon' => array(
        'type'  => 'checkbox',
        'std'   => 0,
        'label' => esc_attr__( 'Hide category icon', 'barberry' ),
      ),
      'expand_all' => array(
        'type'  => 'checkbox',
        'std'   => 0,
        'label' => esc_attr__( 'Expand All', 'barberry' ),
      ),				
    );

    parent::__construct();
  }

  public function widget( $args, $instance ) {
    global $wp_query, $post;

    $count              = isset( $instance['count'] ) ? $instance['count'] : $this->settings['count']['std'];
    $orderby            = isset( $instance['orderby'] ) ? $instance['orderby'] : $this->settings['orderby']['std'];
    $hide_empty         = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : $this->settings['hide_empty']['std'];
    $hide_icon          = isset( $instance['hide_icon'] ) ? $instance['hide_icon'] : $this->settings['hide_icon']['std'];
    $expand_all         = isset( $instance['expand_all'] ) ? $instance['expand_all'] : $this->settings['expand_all']['std'];
    $list_args          = array( 'show_count' => $count, 'taxonomy' => 'product_cat', 'hide_empty' => $hide_empty, 'hide_icon' => $hide_icon, 'expand_all' => $expand_all );
    // $show_children_only = isset( $instance['show_children_only'] ) ? $instance['show_children_only'] : $this->settings['show_children_only']['std'];


    // Menu Order
    $list_args['menu_order'] = false;
    if ( 'order' === $orderby ) {
      $list_args['menu_order'] = 'asc';
    } else {
      $list_args['orderby']    = 'title';
    }

    // Setup Current Category
    $this->current_cat   = false;
    $this->cat_ancestors = array();

    if ( is_tax( 'product_cat' ) ) {

      $this->current_cat   = $wp_query->queried_object;
      $this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );

    } elseif ( is_singular( 'product' ) ) {

      $product_category = wc_get_product_terms( $post->ID, 'product_cat', apply_filters( 'woocommerce_product_categories_widget_product_terms_args', array( 'orderby' => 'parent' ) ) );

      if ( ! empty( $product_category ) ) {
        $this->current_cat   = end( $product_category );
        $this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );
      }
    }

    $this->widget_start( $args, $instance );

    $list_args['walker']                     = new WC_Product_Cat_List_With_Icon_Walker;
    $list_args['title_li']                   = '';
    $list_args['pad_counts']                 = 1;
    $list_args['show_option_none']           = esc_attr__( 'No product categories exist.', 'barberry' );
    $list_args['current_category']           = ( $this->current_cat ) ? $this->current_cat->term_id : '';
    $list_args['current_category_ancestors'] = $this->cat_ancestors;

    echo '<ul class="product-categories-with-icon">';

    $links_html= '';


    print $links_html;

    wp_list_categories( $list_args );

    echo '</ul>';

    $this->widget_end( $args );
  }
}