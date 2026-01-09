<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// Elementor Theme Location: Archive (optional).
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'archive' ) ) {
    // Elementor is handling the archive.
} else {
    echo '<main id="primary" class="inventivity-content" role="main">';
    if ( have_posts() ) {
        while ( have_posts() ) {
            the_post();
            the_content();
        }
    }
    echo '</main>';
}

get_footer();
