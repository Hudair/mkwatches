<?php
/**
 * Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 */

/*
 * If your child theme has more than one .css file (eg. ie.css, style.css, main.css) then
 * you will have to make sure to maintain all of the parent theme dependencies.
 *
 * Make sure you're using the correct handle for loading the parent theme's styles.
 * Failure to use the proper tag will result in a CSS file needlessly being loaded twice.
 * This will usually not affect the site appearance, but it's inefficient and extends your page's loading time.
 *
 * @link https://developer.wordpress.org/themes/advanced-topics/child-themes/
 */
function woodstock_child_enqueue_styles() {
    wp_enqueue_style( 'woodstock-style' , get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'woodstock-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'woodstock-style' ),
        wp_get_theme()->get('Version')
    );
if ( is_rtl() ) {
wp_enqueue_style(  'woodstock-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );
}
}

add_action(  'wp_enqueue_scripts', 'woodstock_child_enqueue_styles',100 );