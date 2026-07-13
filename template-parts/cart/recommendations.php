<?php
/**
 * Product recommendations below the cart.
 *
 * @package Kanapka_Theme
 */

$cart_product_ids = array_map( 'absint', wp_list_pluck( WC()->cart->get_cart(), 'product_id' ) );
$products         = array_values(
	array_filter(
		kanapka_theme_get_popular_products( 12 ),
		static fn( $product ) => ! in_array( $product->get_id(), $cart_product_ids, true )
	)
);
$products = array_slice( $products, 0, 8 );

if ( ! $products ) {
	return;
}
?>
<section class="home-section section popular-products cart-recommendations" aria-labelledby="cart-recommendations-title" data-product-slider>
	<div class="container">
		<div class="section-heading">
			<h2 id="cart-recommendations-title"><?php echo wp_kses_post( __( 'You may also like&hellip;', 'woocommerce' ) ); ?></h2>
		</div>
		<div class="popular-products__carousel">
			<button class="popular-products__arrow popular-products__arrow--previous" type="button" aria-label="<?php esc_attr_e( 'Previous products', 'kanapka-theme' ); ?>" data-product-slider-previous>
				<?php echo kanapka_theme_ui_icon( 'chevron-left', 34 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
			<div class="popular-products__viewport">
				<div class="product-card-grid" data-product-slider-track>
					<?php foreach ( $products as $product ) : ?>
						<?php get_template_part( 'template-parts/components/product-card', null, array( 'product' => $product, 'show_quantity' => true, 'show_quick_view' => false ) ); ?>
					<?php endforeach; ?>
				</div>
			</div>
			<button class="popular-products__arrow popular-products__arrow--next" type="button" aria-label="<?php esc_attr_e( 'Next products', 'kanapka-theme' ); ?>" data-product-slider-next>
				<?php echo kanapka_theme_ui_icon( 'chevron-right', 34 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		</div>
	</div>
</section>
