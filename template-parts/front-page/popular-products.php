<?php
/**
 * Popular products.
 *
 * @package Kanapka_Theme
 */

$products = kanapka_theme_get_popular_products( 5 );

if ( ! $products ) {
	return;
}
?>
<section class="home-section section" aria-labelledby="popular-products-title">
	<div class="container">
		<div class="section-heading">
			<h2 id="popular-products-title"><?php esc_html_e( 'Часто замовляють', 'kanapka-theme' ); ?></h2>
		</div>
		<div class="product-card-grid">
			<?php
			foreach ( $products as $product ) {
				get_template_part( 'template-parts/components/product-card', null, array( 'product' => $product ) );
			}
			?>
		</div>
	</div>
</section>
