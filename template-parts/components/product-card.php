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
	<a class="product-card__media" href="<?php echo esc_url( $product->get_permalink() ); ?>">
		<?php echo $product->get_image( 'kanapka-product-card', array( 'loading' => 'lazy', 'sizes' => '(max-width: 640px) 75vw, 260px' ) ); ?>
	</a>
	<div class="product-card__body">
		<h3><a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
		<div class="product-card__meta">
			<span class="product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
			<?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
				<a class="button product-card__button add_to_cart_button ajax_add_to_cart product_type_<?php echo esc_attr( $product->get_type() ); ?>" href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" data-quantity="1" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" rel="nofollow"><?php echo esc_html( $product->add_to_cart_text() ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</article>
