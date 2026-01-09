# Inventivity Theme (v1.3.0) - MANIFEST

## What changed vs v1.2.0
- Added a **Customizer Panel**: Appearance → Customize → **Inventivity Theme** (keeps options tidy).
- Added a **Fonts** section with Google Fonts optimizations (Elementor-friendly):
  - Preconnect resource hints (ON by default).
  - Force `display=swap` on Google Fonts stylesheet URLs (ON by default).

## Files
- `style.css`
  Theme header + minimal default CSS (can be disabled from Customizer).
- `functions.php`
  Theme setup, Elementor Theme Locations support, WooCommerce ultra-clean wrappers, and performance toggles:
  - Disable theme styles completely
  - Disable emojis
  - Disable oEmbed front-end
  - Disable Dashicons for visitors
  - Disable wp-block-library CSS
  - Disable Global Styles (theme.json output)
  - Disable jQuery Migrate (front-end)
  - WooCommerce: Trim assets on non-shop pages
  - WooCommerce: Disable cart fragments on non-shop pages
  Plus Fonts toggles:
  - Google Fonts: preconnect resource hints
  - Google Fonts: force display=swap
- `header.php`
  Calls Elementor Theme Location `header` when available.
- `footer.php`
  Calls Elementor Theme Location `footer` when available.
- `index.php`
  Minimal loop + supports Elementor Theme Location `archive`.
- `page.php`
  Minimal page template (Elementor-friendly).
- `single.php`
  Minimal single template + supports Elementor Theme Location `single`.
- `woocommerce.php`
  Ultra-clean WooCommerce template that renders `woocommerce_content()` inside a minimal wrapper.
- `screenshot.png`
  Theme screenshot (your provided image).

## Where to enable options
- Appearance → Customize → **Inventivity Theme**

## Notes
- All performance toggles are **OFF by default** (safe).
- Enable them one-by-one and test plugin compatibility.
