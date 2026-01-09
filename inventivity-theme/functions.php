<?php
/**
 * Inventivity Theme functions and definitions.
 *
 * @package Inventivity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'INVENTIVITY_THEME_VERSION', '1.3.0' );

/**
 * Theme setup.
 */
if ( ! function_exists( 'inventivity_theme_setup' ) ) {
    function inventivity_theme_setup() {
        load_theme_textdomain( 'inventivity-theme', get_template_directory() . '/languages' );

        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );

        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            )
        );

        add_theme_support(
            'custom-logo',
            array(
                'height'      => 100,
                'width'       => 400,
                'flex-height' => true,
                'flex-width'  => true,
            )
        );

        add_theme_support( 'align-wide' );
        add_theme_support( 'responsive-embeds' );

        // WooCommerce ultra-clean compatibility.
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }
}
add_action( 'after_setup_theme', 'inventivity_theme_setup' );

/**
 * Helpers.
 */
if ( ! function_exists( 'inventivity_sanitize_checkbox' ) ) {
    function inventivity_sanitize_checkbox( $checked ) {
        return ( isset( $checked ) && true === (bool) $checked );
    }
}

if ( ! function_exists( 'inventivity_is_perf_enabled' ) ) {
    /**
     * Read a boolean theme_mod and allow overriding via filter.
     *
     * @param string $mod_name Theme mod name.
     * @param bool   $default  Default value (safe by default = false).
     * @return bool
     */
    function inventivity_is_perf_enabled( $mod_name, $default = false ) {
        $val = (bool) get_theme_mod( $mod_name, $default );

        /**
         * Filter: inventivity_perf_toggle
         *
         * Allows forcing/enhancing toggles programmatically.
         * Example:
         * add_filter('inventivity_perf_toggle', function($value,$mod){ if($mod==='inventivity_disable_emojis') return true; return $value; }, 10, 2);
         *
         * @param bool   $val      Current value.
         * @param string $mod_name Theme mod key.
         */
        return (bool) apply_filters( 'inventivity_perf_toggle', $val, $mod_name );
    }
}

/**
 * Customizer settings (safe by default).
 */
if ( ! function_exists( 'inventivity_theme_customize_register' ) ) {
    function inventivity_theme_customize_register( $wp_customize ) {
        // Main theme panel (kept intentionally small and practical).
        $wp_customize->add_panel(
            'inventivity_theme_panel',
            array(
                'title'       => __( 'Inventivity Theme', 'inventivity-theme' ),
                'priority'    => 160,
                'description' => __( 'Front-end focused options for Elementor-first builds. Safe-by-default.', 'inventivity-theme' ),
            )
        );

        $wp_customize->add_section(
            'inventivity_theme_options',
            array(
                'title'       => __( 'Performance & Assets', 'inventivity-theme' ),
                'panel'       => 'inventivity_theme_panel',
                'priority'    => 10,
                'description' => __( 'Safe-by-default toggles. Turn ON only what you want.', 'inventivity-theme' ),
            )
        );

        // Fonts section (Google Fonts optimizations).
        $wp_customize->add_section(
            'inventivity_theme_fonts',
            array(
                'title'       => __( 'Fonts', 'inventivity-theme' ),
                'panel'       => 'inventivity_theme_panel',
                'priority'    => 20,
                'description' => __( 'Optimizations for Google Fonts (Elementor-friendly).', 'inventivity-theme' ),
            )
        );

        $wp_customize->add_setting(
            'inventivity_google_fonts_preconnect',
            array(
                'default'           => true,
                'sanitize_callback' => 'inventivity_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );
        $wp_customize->add_control(
            'inventivity_google_fonts_preconnect',
            array(
                'type'        => 'checkbox',
                'section'     => 'inventivity_theme_fonts',
                'label'       => __( 'Add preconnect for Google Fonts', 'inventivity-theme' ),
                'description' => __( 'Adds resource hints for faster font loading (fonts.googleapis.com / fonts.gstatic.com).', 'inventivity-theme' ),
            )
        );

        $wp_customize->add_setting(
            'inventivity_google_fonts_force_swap',
            array(
                'default'           => true,
                'sanitize_callback' => 'inventivity_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );
        $wp_customize->add_control(
            'inventivity_google_fonts_force_swap',
            array(
                'type'        => 'checkbox',
                'section'     => 'inventivity_theme_fonts',
                'label'       => __( 'Force display=swap on Google Fonts URLs', 'inventivity-theme' ),
                'description' => __( 'Ensures Google Fonts stylesheets include display=swap to reduce render-blocking.', 'inventivity-theme' ),
            )
        );

        // 1) Disable theme stylesheet.
        $wp_customize->add_setting(
            'inventivity_disable_theme_styles',
            array(
                'default'           => false,
                'sanitize_callback' => 'inventivity_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );
        $wp_customize->add_control(
            'inventivity_disable_theme_styles',
            array(
                'type'        => 'checkbox',
                'section'     => 'inventivity_theme_options',
                'label'       => __( 'Disable theme styles completely', 'inventivity-theme' ),
                'description' => __( 'Stops enqueueing the theme stylesheet (style.css). Useful if you rely 100% on Elementor styles.', 'inventivity-theme' ),
            )
        );

        // 2) Emojis.
        $wp_customize->add_setting(
            'inventivity_disable_emojis',
            array(
                'default'           => false,
                'sanitize_callback' => 'inventivity_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );
        $wp_customize->add_control(
            'inventivity_disable_emojis',
            array(
                'type'        => 'checkbox',
                'section'     => 'inventivity_theme_options',
                'label'       => __( 'Disable emojis', 'inventivity-theme' ),
                'description' => __( 'Removes emoji scripts/styles and related filters.', 'inventivity-theme' ),
            )
        );

        // 3) oEmbed.
        $wp_customize->add_setting(
            'inventivity_disable_oembed',
            array(
                'default'           => false,
                'sanitize_callback' => 'inventivity_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );
        $wp_customize->add_control(
            'inventivity_disable_oembed',
            array(
                'type'        => 'checkbox',
                'section'     => 'inventivity_theme_options',
                'label'       => __( 'Disable oEmbed front-end', 'inventivity-theme' ),
                'description' => __( 'Disables oEmbed discovery and front-end scripts if you do not use embeds.', 'inventivity-theme' ),
            )
        );

        // 4) Dashicons for visitors.
        $wp_customize->add_setting(
            'inventivity_disable_dashicons_visitors',
            array(
                'default'           => false,
                'sanitize_callback' => 'inventivity_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );
        $wp_customize->add_control(
            'inventivity_disable_dashicons_visitors',
            array(
                'type'        => 'checkbox',
                'section'     => 'inventivity_theme_options',
                'label'       => __( 'Disable Dashicons for visitors', 'inventivity-theme' ),
                'description' => __( 'Keeps Dashicons for logged-in users, disables for non-logged visitors.', 'inventivity-theme' ),
            )
        );

        // 5) Block library CSS.
        $wp_customize->add_setting(
            'inventivity_disable_wp_block_css',
            array(
                'default'           => false,
                'sanitize_callback' => 'inventivity_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );
        $wp_customize->add_control(
            'inventivity_disable_wp_block_css',
            array(
                'type'        => 'checkbox',
                'section'     => 'inventivity_theme_options',
                'label'       => __( 'Disable wp-block-library CSS', 'inventivity-theme' ),
                'description' => __( 'Stops loading core block CSS. Only enable if you do NOT rely on Gutenberg blocks on the front-end.', 'inventivity-theme' ),
            )
        );

        // 6) Global styles (theme.json).
        $wp_customize->add_setting(
            'inventivity_disable_global_styles',
            array(
                'default'           => false,
                'sanitize_callback' => 'inventivity_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );
        $wp_customize->add_control(
            'inventivity_disable_global_styles',
            array(
                'type'        => 'checkbox',
                'section'     => 'inventivity_theme_options',
                'label'       => __( 'Disable Global Styles (theme.json)', 'inventivity-theme' ),
                'description' => __( 'Stops loading wp_global_styles and related inline CSS. Only enable if you do NOT use block themes styles.', 'inventivity-theme' ),
            )
        );

        // 7) jQuery migrate (front-end).
        $wp_customize->add_setting(
            'inventivity_disable_jquery_migrate',
            array(
                'default'           => false,
                'sanitize_callback' => 'inventivity_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );
        $wp_customize->add_control(
            'inventivity_disable_jquery_migrate',
            array(
                'type'        => 'checkbox',
                'section'     => 'inventivity_theme_options',
                'label'       => __( 'Disable jQuery Migrate (front-end)', 'inventivity-theme' ),
                'description' => __( 'Removes jquery-migrate on the front-end. Enable only if your plugins/themes do not rely on old jQuery APIs.', 'inventivity-theme' ),
            )
        );

        // 8) WooCommerce asset trimming.
        $wp_customize->add_setting(
            'inventivity_wc_asset_trim',
            array(
                'default'           => false,
                'sanitize_callback' => 'inventivity_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );
        $wp_customize->add_control(
            'inventivity_wc_asset_trim',
            array(
                'type'        => 'checkbox',
                'section'     => 'inventivity_theme_options',
                'label'       => __( 'WooCommerce: Trim assets on non-shop pages', 'inventivity-theme' ),
                'description' => __( 'Dequeues most WooCommerce CSS/JS outside shop/product/cart/checkout/account. Safe default is OFF.', 'inventivity-theme' ),
            )
        );

        // 9) WooCommerce cart fragments trim.
        $wp_customize->add_setting(
            'inventivity_wc_disable_cart_fragments_offshop',
            array(
                'default'           => false,
                'sanitize_callback' => 'inventivity_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );
        $wp_customize->add_control(
            'inventivity_wc_disable_cart_fragments_offshop',
            array(
                'type'        => 'checkbox',
                'section'     => 'inventivity_theme_options',
                'label'       => __( 'WooCommerce: Disable cart fragments on non-shop pages', 'inventivity-theme' ),
                'description' => __( 'Stops wc-cart-fragments outside shop/cart/checkout/account. Can improve performance if you do not need dynamic cart updates sitewide.', 'inventivity-theme' ),
            )
        );
    }
}
add_action( 'customize_register', 'inventivity_theme_customize_register' );

/**
 * Enqueue styles.
 */
if ( ! function_exists( 'inventivity_theme_enqueue_assets' ) ) {
    function inventivity_theme_enqueue_assets() {
        $disable_styles = inventivity_is_perf_enabled( 'inventivity_disable_theme_styles', false );

        /**
         * Filter to force-disable theme styles programmatically.
         *
         * @param bool $disable_styles Whether to disable theme styles.
         */
        $disable_styles = (bool) apply_filters( 'inventivity_disable_theme_styles', $disable_styles );

        if ( $disable_styles ) {
            return;
        }

        wp_enqueue_style(
            'inventivity-theme-style',
            get_stylesheet_uri(),
            array(),
            INVENTIVITY_THEME_VERSION
        );
    }
}
add_action( 'wp_enqueue_scripts', 'inventivity_theme_enqueue_assets', 20 );

/**
 * Google Fonts optimizations (Elementor-friendly).
 */
if ( ! function_exists( 'inventivity_google_fonts_resource_hints' ) ) {
    function inventivity_google_fonts_resource_hints( $urls, $relation_type ) {
        if ( is_admin() ) {
            return $urls;
        }

        if ( ! inventivity_is_perf_enabled( 'inventivity_google_fonts_preconnect', true ) ) {
            return $urls;
        }

        if ( 'preconnect' === $relation_type ) {
            $urls[] = 'https://fonts.googleapis.com';
            $urls[] = array(
                'href'        => 'https://fonts.gstatic.com',
                'crossorigin' => 'anonymous',
            );
        }

        return $urls;
    }
}
add_filter( 'wp_resource_hints', 'inventivity_google_fonts_resource_hints', 10, 2 );

if ( ! function_exists( 'inventivity_google_fonts_force_display_swap' ) ) {
    function inventivity_google_fonts_force_display_swap( $src, $handle ) {
        if ( is_admin() ) {
            return $src;
        }

        if ( ! inventivity_is_perf_enabled( 'inventivity_google_fonts_force_swap', true ) ) {
            return $src;
        }

        if ( false === strpos( $src, 'fonts.googleapis.com' ) ) {
            return $src;
        }

        // If display is already set, leave it as-is.
        if ( false !== strpos( $src, 'display=' ) ) {
            return $src;
        }

        $glue = ( false === strpos( $src, '?' ) ) ? '?' : '&';
        return $src . $glue . 'display=swap';
    }
}
add_filter( 'style_loader_src', 'inventivity_google_fonts_force_display_swap', 10, 2 );

/**
 * Performance toggles (safe by default = OFF).
 */
if ( ! function_exists( 'inventivity_disable_emojis' ) ) {
    function inventivity_disable_emojis() {
        if ( ! inventivity_is_perf_enabled( 'inventivity_disable_emojis', false ) ) {
            return;
        }

        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        add_filter( 'tiny_mce_plugins', 'inventivity_disable_emojis_tinymce' );
        add_filter( 'wp_resource_hints', 'inventivity_disable_emojis_remove_dns_prefetch', 10, 2 );
    }
}
add_action( 'init', 'inventivity_disable_emojis', 1 );

if ( ! function_exists( 'inventivity_disable_emojis_tinymce' ) ) {
    function inventivity_disable_emojis_tinymce( $plugins ) {
        if ( is_array( $plugins ) ) {
            return array_diff( $plugins, array( 'wpemoji' ) );
        }
        return array();
    }
}

if ( ! function_exists( 'inventivity_disable_emojis_remove_dns_prefetch' ) ) {
    function inventivity_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
        if ( 'dns-prefetch' !== $relation_type ) {
            return $urls;
        }
        $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/15.0.3/svg/' );
        return array_diff( $urls, array( $emoji_svg_url ) );
    }
}

if ( ! function_exists( 'inventivity_disable_oembed' ) ) {
    function inventivity_disable_oembed() {
        if ( ! inventivity_is_perf_enabled( 'inventivity_disable_oembed', false ) ) {
            return;
        }

        // Remove the REST API endpoint.
        remove_action( 'rest_api_init', 'wp_oembed_register_route' );

        // Turn off oEmbed auto discovery.
        add_filter( 'embed_oembed_discover', '__return_false' );

        // Remove oEmbed discovery links.
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

        // Remove oEmbed-specific JavaScript from the front-end and back-end.
        remove_action( 'wp_head', 'wp_oembed_add_host_js' );

        // Remove oEmbed from TinyMCE.
        add_filter( 'tiny_mce_plugins', 'inventivity_disable_oembed_tinymce' );

        // Remove all embeds rewrite rules.
        add_filter( 'rewrite_rules_array', 'inventivity_disable_embeds_rewrites' );
    }
}
add_action( 'init', 'inventivity_disable_oembed', 1 );

if ( ! function_exists( 'inventivity_disable_oembed_tinymce' ) ) {
    function inventivity_disable_oembed_tinymce( $plugins ) {
        if ( is_array( $plugins ) ) {
            return array_diff( $plugins, array( 'wpembed' ) );
        }
        return array();
    }
}

if ( ! function_exists( 'inventivity_disable_embeds_rewrites' ) ) {
    function inventivity_disable_embeds_rewrites( $rules ) {
        foreach ( $rules as $rule => $rewrite ) {
            if ( false !== strpos( $rewrite, 'embed=true' ) ) {
                unset( $rules[ $rule ] );
            }
        }
        return $rules;
    }
}

if ( ! function_exists( 'inventivity_disable_dashicons_for_visitors' ) ) {
    function inventivity_disable_dashicons_for_visitors() {
        if ( ! inventivity_is_perf_enabled( 'inventivity_disable_dashicons_visitors', false ) ) {
            return;
        }
        if ( is_user_logged_in() ) {
            return;
        }
        wp_deregister_style( 'dashicons' );
    }
}
add_action( 'wp_enqueue_scripts', 'inventivity_disable_dashicons_for_visitors', 100 );

if ( ! function_exists( 'inventivity_disable_block_css' ) ) {
    function inventivity_disable_block_css() {
        if ( ! inventivity_is_perf_enabled( 'inventivity_disable_wp_block_css', false ) ) {
            return;
        }
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'wc-block-style' ); // Woo blocks.
    }
}
add_action( 'wp_enqueue_scripts', 'inventivity_disable_block_css', 100 );

if ( ! function_exists( 'inventivity_disable_global_styles' ) ) {
    function inventivity_disable_global_styles() {
        if ( ! inventivity_is_perf_enabled( 'inventivity_disable_global_styles', false ) ) {
            return;
        }
        // WP 5.9+ global styles.
        remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
        remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
        remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
        remove_action( 'in_admin_header', 'wp_global_styles_render_svg_filters' );
        remove_action( 'wp_head', 'wp_print_styles', 8 ); // keep core but helps reduce inline in some setups.
    }
}
add_action( 'init', 'inventivity_disable_global_styles', 1 );

if ( ! function_exists( 'inventivity_disable_jquery_migrate' ) ) {
    function inventivity_disable_jquery_migrate( $scripts ) {
        if ( is_admin() ) {
            return;
        }
        if ( ! inventivity_is_perf_enabled( 'inventivity_disable_jquery_migrate', false ) ) {
            return;
        }

        if ( isset( $scripts->registered['jquery'] ) && ! empty( $scripts->registered['jquery']->deps ) ) {
            $scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, array( 'jquery-migrate' ) );
        }
    }
}
add_action( 'wp_default_scripts', 'inventivity_disable_jquery_migrate' );

/**
 * Elementor Theme Locations:
 * Templates call elementor_theme_do_location() when available.
 */

/**
 * WooCommerce wrappers - keep output minimal and theme-agnostic.
 */
if ( ! function_exists( 'inventivity_woocommerce_wrappers_init' ) ) {
    function inventivity_woocommerce_wrappers_init() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
        remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

        add_action( 'woocommerce_before_main_content', 'inventivity_woocommerce_output_content_wrapper', 10 );
        add_action( 'woocommerce_after_main_content', 'inventivity_woocommerce_output_content_wrapper_end', 10 );

        // Ultra-clean: remove WooCommerce sidebar by default.
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
    }
}
add_action( 'after_setup_theme', 'inventivity_woocommerce_wrappers_init', 20 );

if ( ! function_exists( 'inventivity_woocommerce_output_content_wrapper' ) ) {
    function inventivity_woocommerce_output_content_wrapper() {
        echo '<main id="primary" class="inventivity-content" role="main">';
    }
}

if ( ! function_exists( 'inventivity_woocommerce_output_content_wrapper_end' ) ) {
    function inventivity_woocommerce_output_content_wrapper_end() {
        echo '</main>';
    }
}

/**
 * WooCommerce: Trim assets on non-shop pages (safe default OFF).
 */
if ( ! function_exists( 'inventivity_is_woo_page' ) ) {
    function inventivity_is_woo_page() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return false;
        }

        if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
            return true;
        }

        if ( function_exists( 'is_cart' ) && is_cart() ) {
            return true;
        }

        if ( function_exists( 'is_checkout' ) && is_checkout() ) {
            return true;
        }

        if ( function_exists( 'is_account_page' ) && is_account_page() ) {
            return true;
        }

        return false;
    }
}

if ( ! function_exists( 'inventivity_wc_trim_assets' ) ) {
    function inventivity_wc_trim_assets() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }
        if ( ! inventivity_is_perf_enabled( 'inventivity_wc_asset_trim', false ) ) {
            return;
        }
        if ( inventivity_is_woo_page() ) {
            return;
        }

        // Common WooCommerce styles.
        wp_dequeue_style( 'woocommerce-general' );
        wp_dequeue_style( 'woocommerce-layout' );
        wp_dequeue_style( 'woocommerce-smallscreen' );
        wp_dequeue_style( 'woocommerce-inline' );

        // Woo Blocks / Storefront style handles sometimes.
        wp_dequeue_style( 'woocommerce-block-style' );
        wp_dequeue_style( 'wc-block-style' );

        // Common scripts.
        wp_dequeue_script( 'woocommerce' );
        wp_dequeue_script( 'wc-add-to-cart' );
        wp_dequeue_script( 'wc-cart-fragments' );
        wp_dequeue_script( 'wc-checkout' );
        wp_dequeue_script( 'wc-add-to-cart-variation' );
        wp_dequeue_script( 'wc-single-product' );
        wp_dequeue_script( 'wc-price-slider' );
        wp_dequeue_script( 'wc-credit-card-form' );
    }
}
add_action( 'wp_enqueue_scripts', 'inventivity_wc_trim_assets', 200 );

if ( ! function_exists( 'inventivity_wc_disable_cart_fragments_offshop' ) ) {
    function inventivity_wc_disable_cart_fragments_offshop() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }
        if ( ! inventivity_is_perf_enabled( 'inventivity_wc_disable_cart_fragments_offshop', false ) ) {
            return;
        }
        if ( inventivity_is_woo_page() ) {
            return;
        }
        wp_dequeue_script( 'wc-cart-fragments' );
    }
}
add_action( 'wp_enqueue_scripts', 'inventivity_wc_disable_cart_fragments_offshop', 201 );
