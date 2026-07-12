<?php
/**
 * Product archive loop.
 *
 * @package Kanapka_Theme
 */

?>
<div class="catalogue-toolbar">
	<div class="catalogue-view-switcher" aria-label="<?php esc_attr_e( 'Вигляд товарів', 'kanapka-theme' ); ?>" data-catalogue-view-switcher>
		<span><?php esc_html_e( 'Відобразити:', 'kanapka-theme' ); ?></span>
		<button type="button" aria-label="<?php esc_attr_e( 'Відобразити списком', 'kanapka-theme' ); ?>" aria-pressed="false" data-catalogue-view="list">
			<?php echo kanapka_theme_ui_icon( 'view-list', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
		</button>
		<button type="button" aria-label="<?php esc_attr_e( 'Відобразити сіткою', 'kanapka-theme' ); ?>" aria-pressed="true" data-catalogue-view="grid">
			<?php echo kanapka_theme_ui_icon( 'view-grid', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
		</button>
	</div>
	<div class="catalogue-toolbar__count"><?php woocommerce_result_count(); ?></div>
	<?php woocommerce_catalog_ordering(); ?>
</div>

<?php if ( woocommerce_product_loop() ) : ?>
	<div class="catalogue-product-grid" data-catalogue-products data-view="grid">
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
