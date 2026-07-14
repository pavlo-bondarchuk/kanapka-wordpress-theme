<?php
/**
 * Catalogue sidebar.
 *
 * @package Kanapka_Theme
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'categories_only'        => false,
		'show_all'               => false,
		'mobile_category_toggle' => false,
	)
);

$categories      = kanapka_theme_get_shop_categories();
$categories_only = (bool) $args['categories_only'];
$new_items       = $categories_only ? array() : kanapka_theme_get_shop_new_products( 4 );
$min_price  = isset( $_GET['min_price'] ) ? wc_format_decimal( wp_unslash( $_GET['min_price'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$max_price  = isset( $_GET['max_price'] ) ? wc_format_decimal( wp_unslash( $_GET['max_price'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$price_limit = kanapka_theme_get_catalogue_max_price();
$selected_min_price = '' !== $min_price ? max( 0, (int) $min_price ) : 0;
$selected_max_price = '' !== $max_price ? min( $price_limit, (int) $max_price ) : $price_limit;
$visible_category_limit = $args['show_all'] ? PHP_INT_MAX : 8;
?>
<aside class="catalogue-sidebar" aria-label="<?php esc_attr_e( 'Catalogue filters', 'kanapka-theme' ); ?>">
	<button class="catalogue-sidebar__mobile-toggle" type="button" aria-expanded="false" aria-controls="catalogue-sidebar-content" data-catalogue-sidebar-toggle data-open-label="<?php echo esc_attr( $args['mobile_category_toggle'] ? __( 'Show all categories', 'kanapka-theme' ) : __( 'Open catalogue filters', 'kanapka-theme' ) ); ?>" data-close-label="<?php echo esc_attr( $args['mobile_category_toggle'] ? __( 'Show less', 'kanapka-theme' ) : __( 'Close catalogue filters', 'kanapka-theme' ) ); ?>">
		<span<?php echo $args['mobile_category_toggle'] ? ' data-catalogue-sidebar-toggle-label' : ''; ?>><?php echo esc_html( $args['mobile_category_toggle'] ? __( 'Show all categories', 'kanapka-theme' ) : __( 'Catalogue filters', 'kanapka-theme' ) ); ?></span>
		<?php echo kanapka_theme_ui_icon( 'chevron-right', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
	</button>
	<div class="catalogue-sidebar__content" id="catalogue-sidebar-content" data-catalogue-sidebar-content>
	<?php if ( $categories ) : ?>
		<section class="catalogue-panel">
			<h2><?php esc_html_e( 'Categories', 'kanapka-theme' ); ?></h2>
			<nav class="catalogue-sidebar__categories" aria-label="<?php esc_attr_e( 'Product categories', 'kanapka-theme' ); ?>">
				<a class="catalogue-sidebar__category<?php echo is_shop() ? ' is-active' : ''; ?>" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
					<span><?php esc_html_e( 'All categories', 'kanapka-theme' ); ?></span>
				</a>
				<?php foreach ( $categories as $category_index => $category ) : ?>
					<?php
					$category_url = get_term_link( $category );

					if ( is_wp_error( $category_url ) ) {
						continue;
					}
					?>
					<a class="catalogue-sidebar__category<?php echo is_product_category( $category->term_id ) ? ' is-active' : ''; ?>" href="<?php echo esc_url( $category_url ); ?>"<?php echo $category_index >= $visible_category_limit ? ' hidden data-catalogue-category-extra' : ''; ?>>
						<span><?php echo esc_html( $category->name ); ?></span>
						<small><?php echo esc_html( number_format_i18n( $category->count ) ); ?></small>
					</a>
				<?php endforeach; ?>
			</nav>
			<?php if ( count( $categories ) > $visible_category_limit ) : ?>
				<button class="catalogue-sidebar__toggle" type="button" aria-expanded="false" data-catalogue-category-toggle data-show-label="<?php esc_attr_e( 'Show all categories', 'kanapka-theme' ); ?>" data-hide-label="<?php esc_attr_e( 'Show less', 'kanapka-theme' ); ?>">
					<span data-catalogue-category-toggle-label><?php esc_html_e( 'Show all categories', 'kanapka-theme' ); ?></span>
					<?php echo kanapka_theme_ui_icon( 'chevron-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
				</button>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<?php if ( ! $categories_only ) : ?>
	<section class="catalogue-panel">
		<h2><?php esc_html_e( 'Filter by price', 'kanapka-theme' ); ?></h2>
		<form class="catalogue-price-filter" method="get" data-price-range-filter>
			<div class="catalogue-price-filter__range" style="--range-start: 0%; --range-end: 100%;" data-price-range>
				<div class="catalogue-price-filter__rail" aria-hidden="true"><span data-price-range-fill></span></div>
				<input type="range" min="0" max="<?php echo esc_attr( $price_limit ); ?>" step="1" value="<?php echo esc_attr( $selected_min_price ); ?>" aria-label="<?php esc_attr_e( 'Minimum price', 'kanapka-theme' ); ?>" data-price-range-min>
				<input type="range" min="0" max="<?php echo esc_attr( $price_limit ); ?>" step="1" value="<?php echo esc_attr( $selected_max_price ); ?>" aria-label="<?php esc_attr_e( 'Maximum price', 'kanapka-theme' ); ?>" data-price-range-max>
			</div>
			<label>
				<span><?php esc_html_e( 'from', 'kanapka-theme' ); ?></span>
				<input type="number" name="min_price" min="0" max="<?php echo esc_attr( $price_limit ); ?>" step="1" value="<?php echo esc_attr( $selected_min_price ); ?>" data-price-input-min>
			</label>
			<label>
				<span><?php esc_html_e( 'to', 'kanapka-theme' ); ?></span>
				<input type="number" name="max_price" min="0" max="<?php echo esc_attr( $price_limit ); ?>" step="1" value="<?php echo esc_attr( $selected_max_price ); ?>" data-price-input-max>
			</label>
			<button class="button" type="submit"><?php esc_html_e( 'Apply', 'kanapka-theme' ); ?></button>
			<?php if ( '' !== $min_price || '' !== $max_price ) : ?>
				<a class="catalogue-price-filter__reset" href="<?php echo esc_url( strtok( home_url( add_query_arg( array() ) ), '?' ) ); ?>"><?php esc_html_e( 'Reset filter', 'kanapka-theme' ); ?></a>
			<?php endif; ?>
		</form>
	</section>
	<?php endif; ?>

	<?php if ( $new_items ) : ?>
		<section class="catalogue-panel">
			<h2><?php esc_html_e( 'New products', 'kanapka-theme' ); ?></h2>
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
	</div>
</aside>
