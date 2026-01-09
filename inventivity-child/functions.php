<?php
/**
 * Inventivity Theme Child functions and definitions.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'inventivity_theme_child_enqueue_styles' ) ) {
    function inventivity_theme_child_enqueue_styles() {
        $parent_handle = 'inventivity-theme-style';

        // Parent stylesheet (only enqueues if parent didn't disable its styles).
        wp_enqueue_style(
            $parent_handle,
            get_template_directory_uri() . '/style.css',
            array(),
            wp_get_theme( get_template() )->get( 'Version' )
        );

        // Child stylesheet.
        wp_enqueue_style(
            'inventivity-theme-child-style',
            get_stylesheet_uri(),
            array( $parent_handle ),
            wp_get_theme()->get( 'Version' )
        );
    }
}
add_action( 'wp_enqueue_scripts', 'inventivity_theme_child_enqueue_styles', 30 );
