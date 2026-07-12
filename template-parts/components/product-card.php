<?php
/**
 * Reusable WooCommerce product card.
 *
 * @package Kanapka_Theme
 */

$product = $args['product'] ?? null;

if ( ! $product instanceof WC_Product || ! $product->is_visible() ) {
	return;
}
?>
<article class="product-card card">
	<div class="product-card__media">
		<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
			<?php echo $product->get_image( 'kanapka-product-card', array( 'loading' => 'lazy', 'sizes' => '(max-width: 640px) 75vw, 260px' ) ); ?>
		</a>
		<button class="product-card__quick-view" type="button" data-product-quick-view="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Переглянути товар: %s', 'kanapka-theme' ), $product->get_name() ) ); ?>">
			<?php echo kanapka_theme_ui_icon( 'search', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
			<span><?php esc_html_e( 'Переглянути', 'kanapka-theme' ); ?></span>
		</button>
	</div>
	<div class="product-card__body">
		<h3><a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
		<?php if ( $product->get_short_description() ) : ?>
			<div class="product-card__summary"><?php echo wp_kses_post( wpautop( $product->get_short_description() ) ); ?></div>
		<?php endif; ?>
		<div class="product-card__meta">
			<span class="product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
			<?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
				<a class="button product-card__button add_to_cart_button product_type_<?php echo esc_attr( $product->get_type() ); ?>" href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" data-quantity="1" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" data-kanapka-add-to-cart rel="nofollow"><span><?php echo esc_html( $product->add_to_cart_text() ); ?></span></a>
			<?php endif; ?>
		</div>
	</div>
</article>
