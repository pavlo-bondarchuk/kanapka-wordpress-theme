<?php
/**
 * Popular products.
 *
 * @package Kanapka_Theme
 */

$products = kanapka_theme_get_popular_products( 12 );

if ( ! $products ) {
	return;
}
?>
<section class="home-section section popular-products" aria-labelledby="popular-products-title" data-product-slider>
	<div class="container">
		<div class="section-heading">
			<h2 id="popular-products-title"><?php esc_html_e( 'Часто замовляють', 'kanapka-theme' ); ?></h2>
		</div>
		<div class="popular-products__carousel">
			<button class="popular-products__arrow popular-products__arrow--previous" type="button" aria-label="<?php esc_attr_e( 'Попередні товари', 'kanapka-theme' ); ?>" data-product-slider-previous>
				<?php echo kanapka_theme_ui_icon( 'chevron-left', 34 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
			</button>
			<div class="popular-products__viewport">
				<div class="product-card-grid" data-product-slider-track>
					<?php
					foreach ( $products as $product ) {
						get_template_part( 'template-parts/components/product-card', null, array( 'product' => $product, 'show_quantity' => true ) );
					}
					?>
				</div>
			</div>
			<button class="popular-products__arrow popular-products__arrow--next" type="button" aria-label="<?php esc_attr_e( 'Наступні товари', 'kanapka-theme' ); ?>" data-product-slider-next>
				<?php echo kanapka_theme_ui_icon( 'chevron-right', 34 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
			</button>
		</div>
	</div>
</section>
