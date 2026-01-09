<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Elementor Theme Location: Footer.
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'footer' ) ) {
    // Elementor is handling the footer.
} else {
    // Minimal fallback (intentionally empty).
}

wp_footer();
?>
</body>
</html>
