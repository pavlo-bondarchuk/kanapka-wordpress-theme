<?php
/**
 * Reusable WooCommerce product card.
 *
 * @package Kanapka_Theme
 */

$product = $args['product'] ?? null;
$show_quantity = ! empty( $args['show_quantity'] );
$show_quick_view = ! isset( $args['show_quick_view'] ) || (bool) $args['show_quick_view'];

if ( ! $product instanceof WC_Product || ! $product->is_visible() ) {
	return;
}
?>
<article class="product-card card">
	<div class="product-card__media">
		<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
			<?php echo $product->get_image( 'kanapka-product-card', array( 'loading' => 'lazy', 'sizes' => '(max-width: 640px) 75vw, 260px' ) ); ?>
		</a>
		<?php if ( $show_quick_view ) : ?>
			<button class="product-card__quick-view" type="button" data-product-quick-view="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Preview product: %s', 'kanapka-theme' ), $product->get_name() ) ); ?>">
				<?php echo kanapka_theme_ui_icon( 'search', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
				<span><?php esc_html_e( 'View', 'kanapka-theme' ); ?></span>
			</button>
		<?php endif; ?>
	</div>
	<div class="product-card__body">
		<h3><a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
		<?php if ( $product->get_short_description() ) : ?>
			<div class="product-card__summary"><?php echo wp_kses_post( wpautop( $product->get_short_description() ) ); ?></div>
		<?php endif; ?>
		<div class="product-card__meta">
			<div class="product-card__purchase-row">
				<span class="product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
				<?php if ( $show_quantity && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) : ?>
					<div class="quantity-control quantity-control--compact" data-quantity-control>
						<?php echo kanapka_theme_quantity_step_button( 'decrease', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Controlled theme markup. ?>
						<input class="product-card__quantity" type="number" min="1" step="1" value="1" inputmode="numeric" aria-label="<?php echo esc_attr( sprintf( __( 'Product quantity: %s', 'kanapka-theme' ), $product->get_name() ) ); ?>" data-product-card-quantity>
						<?php echo kanapka_theme_quantity_step_button( 'increase', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Controlled theme markup. ?>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
				<a class="button product-card__button add_to_cart_button product_type_<?php echo esc_attr( $product->get_type() ); ?>" href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" data-quantity="1" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" data-kanapka-add-to-cart rel="nofollow" aria-label="<?php echo esc_attr( $product->add_to_cart_text() ); ?>"><span class="product-card__button-label"><?php echo esc_html( $product->add_to_cart_text() ); ?></span><span class="product-card__button-icon" aria-hidden="true"><?php echo kanapka_theme_ui_icon( 'cart', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?></span></a>
			<?php endif; ?>
		</div>
	</div>
</article>
