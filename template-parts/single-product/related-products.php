<?php
/**
 * Related products using the shared product carousel and card.
 *
 * @package Kanapka_Theme
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$related_ids = wc_get_related_products( $product->get_id(), 12, array( $product->get_id() ) );
$products = array_filter( array_map( 'wc_get_product', $related_ids ) );

if ( ! $products ) {
	return;
}
?>
<section class="home-section section popular-products related-products" aria-labelledby="related-products-title" data-product-slider>
	<div class="container">
		<div class="section-heading">
			<h2 id="related-products-title"><?php esc_html_e( 'Related products', 'kanapka-theme' ); ?></h2>
		</div>
		<div class="popular-products__carousel">
			<button class="popular-products__arrow popular-products__arrow--previous" type="button" aria-label="<?php esc_attr_e( 'Previous related products', 'kanapka-theme' ); ?>" data-product-slider-previous>
				<?php echo kanapka_theme_ui_icon( 'chevron-left', 34 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
			<div class="popular-products__viewport">
				<div class="product-card-grid" data-product-slider-track>
					<?php foreach ( $products as $related_product ) : ?>
						<?php get_template_part( 'template-parts/components/product-card', null, array( 'product' => $related_product, 'show_quantity' => true ) ); ?>
					<?php endforeach; ?>
				</div>
			</div>
			<button class="popular-products__arrow popular-products__arrow--next" type="button" aria-label="<?php esc_attr_e( 'Next related products', 'kanapka-theme' ); ?>" data-product-slider-next>
				<?php echo kanapka_theme_ui_icon( 'chevron-right', 34 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		</div>
	</div>
</section>
