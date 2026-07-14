<?php
/**
 * Shared template output helpers.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render the shared site breadcrumb.
 */
function kanapka_theme_breadcrumb() {
	if ( ! function_exists( 'woocommerce_breadcrumb' ) || is_front_page() ) {
		return;
	}

	woocommerce_breadcrumb(
		array(
			'delimiter'   => '<span class="site-breadcrumb__separator" aria-hidden="true">' . kanapka_theme_ui_icon( 'chevron-right', 14 ) . '</span>',
			'wrap_before' => '<nav class="woocommerce-breadcrumb site-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'woocommerce' ) . '">',
			'wrap_after'  => '</nav>',
		)
	);
}
