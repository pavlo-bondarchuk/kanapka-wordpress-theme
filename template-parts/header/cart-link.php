<?php
/**
 * Interactive WooCommerce mini-cart.
 *
 * @package Kanapka_Theme
 */

if ( ! function_exists( 'wc_get_cart_url' ) ) {
	return;
}

$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
?>
<div class="header-popup header-cart kanapka-mini-cart-fragment" data-header-popup="cart">
	<button class="icon-button" type="button" aria-expanded="false" aria-controls="header-mini-cart" aria-label="<?php echo esc_attr( sprintf( __( 'Cart, %d items', 'kanapka-theme' ), $cart_count ) ); ?>" data-header-popup-button>
		<svg aria-hidden="true" viewBox="0 0 24 24" width="24" height="24"><path d="M3 4h2l2.2 10.1a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.4L21 8H7m3 11a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm9 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
		<span class="header-cart__count"><?php echo esc_html( $cart_count ); ?></span>
	</button>
	<div id="header-mini-cart" class="header-popover header-mini-cart" hidden data-header-popup-panel>
		<div class="header-popover__heading">
			<strong><?php esc_html_e( 'Cart', 'kanapka-theme' ); ?></strong>
			<button type="button" aria-label="<?php esc_attr_e( 'Close cart', 'kanapka-theme' ); ?>" data-header-popup-close>&times;</button>
		</div>
		<div class="header-mini-cart__content widget_shopping_cart_content">
			<?php woocommerce_mini_cart(); ?>
		</div>
	</div>
</div>

