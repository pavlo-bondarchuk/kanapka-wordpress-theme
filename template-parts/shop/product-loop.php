<?php
/**
 * Product archive loop.
 *
 * @package Kanapka_Theme
 */

?>
<div class="catalogue-toolbar">
	<div class="catalogue-toolbar__left">
		<div class="catalogue-view-switcher" aria-label="<?php esc_attr_e( 'Product view', 'kanapka-theme' ); ?>" data-catalogue-view-switcher>
			<span><?php esc_html_e( 'Display:', 'kanapka-theme' ); ?></span>
			<button type="button" aria-label="<?php esc_attr_e( 'Display as a list', 'kanapka-theme' ); ?>" aria-pressed="false" data-catalogue-view="list">
				<?php echo kanapka_theme_ui_icon( 'view-list', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
			</button>
			<button type="button" aria-label="<?php esc_attr_e( 'Display as a grid', 'kanapka-theme' ); ?>" aria-pressed="true" data-catalogue-view="grid">
				<?php echo kanapka_theme_ui_icon( 'view-grid', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
			</button>
		</div>
		<div class="catalogue-toolbar__count"><?php woocommerce_result_count(); ?></div>
	</div>
	<?php woocommerce_catalog_ordering(); ?>
</div>

<?php if ( woocommerce_product_loop() ) : ?>
	<div class="catalogue-product-grid" data-catalogue-products data-view="grid">
		<?php
		while ( have_posts() ) :
			the_post();

			$product = wc_get_product( get_the_ID() );

			if ( $product ) {
				get_template_part( 'template-parts/components/product-card', null, array( 'product' => $product, 'show_quantity' => true ) );
			}
		endwhile;
		?>
	</div>
	<?php woocommerce_pagination(); ?>
<?php else : ?>
	<div class="catalogue-empty card">
		<h2><?php esc_html_e( 'No products found', 'kanapka-theme' ); ?></h2>
		<p><?php esc_html_e( 'Try changing the filters or return to all categories.', 'kanapka-theme' ); ?></p>
		<a class="button" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Go to catalogue', 'kanapka-theme' ); ?></a>
	</div>
<?php endif; ?>
