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
	<button class="icon-button" type="button" aria-expanded="false" aria-controls="header-mini-cart" aria-label="<?php echo esc_attr( sprintf( __( 'Кошик, %d товарів', 'kanapka-theme' ), $cart_count ) ); ?>" data-header-popup-button>
		<?php echo kanapka_theme_ui_icon( 'cart', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
		<span class="header-cart__count"><?php echo esc_html( $cart_count ); ?></span>
	</button>
	<div id="header-mini-cart" class="header-popover header-mini-cart" hidden data-header-popup-panel>
		<div class="header-popover__heading">
			<div class="header-mini-cart__heading">
				<strong><?php esc_html_e( 'Кошик', 'kanapka-theme' ); ?></strong>
				<span>
					<?php
					printf(
						/* translators: %s is the number of products in the cart. */
						esc_html( _n( '%s товар', '%s товарів', $cart_count, 'kanapka-theme' ) ),
						esc_html( number_format_i18n( $cart_count ) )
					);
					?>
				</span>
			</div>
			<button type="button" aria-label="<?php esc_attr_e( 'Закрити кошик', 'kanapka-theme' ); ?>" data-header-popup-close>&times;</button>
		</div>
		<div class="header-mini-cart__content widget_shopping_cart_content">
			<?php
			add_filter( 'woocommerce_cart_item_name', 'kanapka_theme_mini_cart_product_name', 10, 1 );
			add_filter( 'woocommerce_cart_item_remove_link', 'kanapka_theme_mini_cart_remove_link', 10, 1 );
			woocommerce_mini_cart();
			remove_filter( 'woocommerce_cart_item_name', 'kanapka_theme_mini_cart_product_name', 10 );
			remove_filter( 'woocommerce_cart_item_remove_link', 'kanapka_theme_mini_cart_remove_link', 10 );
			?>
		</div>
	</div>
</div>
