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
 * Add a line total beside the quantity in mini-cart items.
 *
 * @param string $quantity_html Existing quantity markup.
 * @param array  $cart_item     Cart item data.
 * @return string
 */
function kanapka_theme_mini_cart_item_quantity( $quantity_html, $cart_item ) {
	$product  = isset( $cart_item['data'] ) ? $cart_item['data'] : null;
	$quantity = isset( $cart_item['quantity'] ) ? (int) $cart_item['quantity'] : 0;

	if ( ! $product instanceof WC_Product || ! WC()->cart || $quantity < 1 ) {
		return $quantity_html;
	}

	$unit_price = WC()->cart->get_product_price( $product );
	$line_total = WC()->cart->get_product_subtotal( $product, $quantity );

	return sprintf(
		'<span class="header-mini-cart__item-meta"><span class="quantity">%1$s &times; %2$s</span><span class="header-mini-cart__line-total">%3$s</span></span>',
		esc_html( number_format_i18n( $quantity ) ),
		wp_kses_post( $unit_price ),
		wp_kses_post( $line_total )
	);
}
add_filter( 'woocommerce_widget_cart_item_quantity', 'kanapka_theme_mini_cart_item_quantity', 10, 2 );

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
