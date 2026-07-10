<?php
/**
 * WooCommerce integration.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Set image dimensions that match the design card ratio.
 *
 * @param array $size Existing image size.
 * @return array
 */
function kanapka_theme_product_thumbnail_size( $size ) {
	$size['width']  = 640;
	$size['height'] = 480;
	$size['crop']   = 1;

	return $size;
}
add_filter( 'woocommerce_get_image_size_thumbnail', 'kanapka_theme_product_thumbnail_size' );

/**
 * Keep the complete mini-cart popup synchronized after AJAX cart updates.
 *
 * @param array $fragments WooCommerce fragments.
 * @return array
 */
function kanapka_theme_mini_cart_fragments( $fragments ) {
	ob_start();
	get_template_part( 'template-parts/header/cart-link' );
	$fragments['div.kanapka-mini-cart-fragment'] = (string) ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'kanapka_theme_mini_cart_fragments' );
