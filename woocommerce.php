<?php
/**
 * WooCommerce main template file.
 *
 * @package Inventivity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

echo '<main id="primary" class="inventivity-content" role="main">';
if ( function_exists( 'woocommerce_content' ) ) {
    woocommerce_content();
}
echo '</main>';

get_footer();
