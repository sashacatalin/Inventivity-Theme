<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// Elementor Theme Location: Single (optional).
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'single' ) ) {
    // Elementor is handling single.
} else {
    echo '<main id="primary" class="inventivity-content" role="main">';
    while ( have_posts() ) {
        the_post();
        the_content();
    }
    echo '</main>';
}

get_footer();
