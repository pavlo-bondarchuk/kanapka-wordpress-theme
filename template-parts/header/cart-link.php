<?php
/**
 * WooCommerce cart link.
 *
 * @package Kanapka_Theme
 */

if ( ! function_exists( 'wc_get_cart_url' ) ) {
	return;
}

$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
?>
<a class="header-cart icon-button" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Cart, %d items', 'kanapka-theme' ), $cart_count ) ); ?>">
	<svg aria-hidden="true" viewBox="0 0 24 24" width="24" height="24"><path d="M3 4h2l2.2 10.1a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.4L21 8H7m3 11a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm9 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
	<span class="header-cart__count"><?php echo esc_html( $cart_count ); ?></span>
</a>

