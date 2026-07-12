<?php
/**
 * Catalogue sidebar.
 *
 * @package Kanapka_Theme
 */

$categories = kanapka_theme_get_shop_categories();
$new_items  = kanapka_theme_get_shop_new_products( 4 );
$min_price  = isset( $_GET['min_price'] ) ? wc_format_decimal( wp_unslash( $_GET['min_price'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$max_price  = isset( $_GET['max_price'] ) ? wc_format_decimal( wp_unslash( $_GET['max_price'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
?>
<aside class="catalogue-sidebar" aria-label="<?php esc_attr_e( 'Фільтри каталогу', 'kanapka-theme' ); ?>">
	<?php if ( $categories ) : ?>
		<section class="catalogue-panel">
			<h2><?php esc_html_e( 'Категорії', 'kanapka-theme' ); ?></h2>
			<nav class="catalogue-sidebar__categories" aria-label="<?php esc_attr_e( 'Категорії товарів', 'kanapka-theme' ); ?>">
				<a class="catalogue-sidebar__category<?php echo is_shop() ? ' is-active' : ''; ?>" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
					<span><?php esc_html_e( 'Усі категорії', 'kanapka-theme' ); ?></span>
				</a>
				<?php foreach ( $categories as $category ) : ?>
					<?php
					$category_url = get_term_link( $category );

					if ( is_wp_error( $category_url ) ) {
						continue;
					}
					?>
					<a class="catalogue-sidebar__category<?php echo is_product_category( $category->term_id ) ? ' is-active' : ''; ?>" href="<?php echo esc_url( $category_url ); ?>">
						<span><?php echo esc_html( $category->name ); ?></span>
						<small><?php echo esc_html( number_format_i18n( $category->count ) ); ?></small>
					</a>
				<?php endforeach; ?>
			</nav>
		</section>
	<?php endif; ?>

	<section class="catalogue-panel">
		<h2><?php esc_html_e( 'Фільтр за ціною', 'kanapka-theme' ); ?></h2>
		<form class="catalogue-price-filter" method="get">
			<label>
				<span><?php esc_html_e( 'від', 'kanapka-theme' ); ?></span>
				<input type="number" name="min_price" min="0" step="1" value="<?php echo esc_attr( $min_price ); ?>" placeholder="0">
			</label>
			<label>
				<span><?php esc_html_e( 'до', 'kanapka-theme' ); ?></span>
				<input type="number" name="max_price" min="0" step="1" value="<?php echo esc_attr( $max_price ); ?>" placeholder="1000">
			</label>
			<button class="button" type="submit"><?php esc_html_e( 'Застосувати', 'kanapka-theme' ); ?></button>
			<?php if ( '' !== $min_price || '' !== $max_price ) : ?>
				<a class="catalogue-price-filter__reset" href="<?php echo esc_url( strtok( home_url( add_query_arg( array() ) ), '?' ) ); ?>"><?php esc_html_e( 'Скинути фільтр', 'kanapka-theme' ); ?></a>
			<?php endif; ?>
		</form>
	</section>

	<?php if ( $new_items ) : ?>
		<section class="catalogue-panel">
			<h2><?php esc_html_e( 'Новинки', 'kanapka-theme' ); ?></h2>
			<ul class="catalogue-new-products">
				<?php foreach ( $new_items as $product ) : ?>
					<li>
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
							<?php echo $product->get_image( 'thumbnail', array( 'loading' => 'lazy', 'sizes' => '56px' ) ); ?>
							<span>
								<strong><?php echo esc_html( $product->get_name() ); ?></strong>
								<small><?php echo wp_kses_post( $product->get_price_html() ); ?></small>
							</span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>
</aside>
