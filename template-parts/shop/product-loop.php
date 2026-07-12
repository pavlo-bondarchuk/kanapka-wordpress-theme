<?php
/**
 * Product archive loop.
 *
 * @package Kanapka_Theme
 */

?>
<div class="catalogue-toolbar">
	<?php woocommerce_result_count(); ?>
	<?php woocommerce_catalog_ordering(); ?>
</div>

<?php if ( woocommerce_product_loop() ) : ?>
	<div class="catalogue-product-grid">
		<?php
		while ( have_posts() ) :
			the_post();

			$product = wc_get_product( get_the_ID() );

			if ( $product ) {
				get_template_part( 'template-parts/components/product-card', null, array( 'product' => $product ) );
			}
		endwhile;
		?>
	</div>
	<?php woocommerce_pagination(); ?>
<?php else : ?>
	<div class="catalogue-empty card">
		<h2><?php esc_html_e( 'Товари не знайдено', 'kanapka-theme' ); ?></h2>
		<p><?php esc_html_e( 'Спробуйте змінити фільтр або повернутися до всіх категорій.', 'kanapka-theme' ); ?></p>
		<a class="button" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'До каталогу', 'kanapka-theme' ); ?></a>
	</div>
<?php endif; ?>
