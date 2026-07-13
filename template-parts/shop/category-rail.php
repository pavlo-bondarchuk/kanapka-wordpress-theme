<?php
/**
 * Horizontal product category rail.
 *
 * @package Kanapka_Theme
 */

$current_category = get_queried_object();

if ( ! $current_category instanceof WP_Term || 'product_cat' !== $current_category->taxonomy ) {
	return;
}

$categories = kanapka_theme_get_shop_child_categories( $current_category->term_id );

if ( ! $categories ) {
	return;
}
?>
<section class="catalogue-category-rail container" aria-label="<?php esc_attr_e( 'Subcategories', 'kanapka-theme' ); ?>" data-category-strip>
	<div class="catalogue-category-rail__track" data-category-strip-track>
		<?php foreach ( $categories as $category ) : ?>
			<?php
			$thumbnail_id = absint( get_term_meta( $category->term_id, 'thumbnail_id', true ) );
			$category_url = get_term_link( $category );

			if ( is_wp_error( $category_url ) ) {
				continue;
			}
			?>
			<a class="catalogue-category-rail__item" href="<?php echo esc_url( $category_url ); ?>">
				<span class="catalogue-category-rail__media">
					<?php if ( $thumbnail_id ) : ?>
						<?php echo wp_get_attachment_image( $thumbnail_id, 'kanapka-category', false, array( 'alt' => $category->name, 'loading' => 'lazy', 'sizes' => '(max-width: 640px) 72vw, 240px' ) ); ?>
					<?php endif; ?>
				</span>
				<span class="catalogue-category-rail__body">
					<strong><?php echo esc_html( $category->name ); ?></strong>
					<small><?php echo esc_html( number_format_i18n( $category->count ) ); ?></small>
				</span>
			</a>
		<?php endforeach; ?>
	</div>
	<button class="catalogue-category-rail__arrow" type="button" aria-label="<?php esc_attr_e( 'Show next categories', 'kanapka-theme' ); ?>" data-category-strip-next>
		<?php echo kanapka_theme_ui_icon( 'chevron-right', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
	</button>
</section>
