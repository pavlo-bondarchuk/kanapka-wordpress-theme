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

