<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

echo '<main id="primary" class="inventivity-content" role="main">';
while ( have_posts() ) {
    the_post();
    the_content();
}
echo '</main>';

get_footer();
