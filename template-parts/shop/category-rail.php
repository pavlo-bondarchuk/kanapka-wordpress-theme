<?php
/**
 * Horizontal product category rail.
 *
 * @package Kanapka_Theme
 */

$categories = kanapka_theme_get_shop_categories( 12 );

if ( ! $categories ) {
	return;
}
?>
<section class="catalogue-category-rail container" aria-label="<?php esc_attr_e( 'Популярні категорії', 'kanapka-theme' ); ?>" data-category-strip>
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
				<?php if ( $thumbnail_id ) : ?>
					<?php echo wp_get_attachment_image( $thumbnail_id, 'kanapka-category', false, array( 'alt' => $category->name, 'loading' => 'lazy', 'sizes' => '(max-width: 640px) 34vw, 120px' ) ); ?>
				<?php endif; ?>
				<span><?php echo esc_html( $category->name ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>
	<button class="catalogue-category-rail__arrow" type="button" aria-label="<?php esc_attr_e( 'Показати наступні категорії', 'kanapka-theme' ); ?>" data-category-strip-next>
		<?php echo kanapka_theme_ui_icon( 'chevron-right', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
	</button>
</section>
