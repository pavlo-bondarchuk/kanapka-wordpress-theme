<?php
/**
 * Compact category strip.
 *
 * @package Kanapka_Theme
 */

$categories = kanapka_theme_get_home_categories( 8 );

if ( ! $categories ) {
	return;
}
?>
<section class="category-strip container" aria-label="<?php esc_attr_e( 'Популярні категорії', 'kanapka-theme' ); ?>" data-category-strip>
	<div class="category-strip__track" data-category-strip-track>
		<?php
		foreach ( $categories as $category ) {
			get_template_part( 'template-parts/components/category-card', null, array( 'category' => $category, 'compact' => true ) );
		}
		?>
	</div>
	<?php if ( count( $categories ) > 5 ) : ?>
		<button class="category-strip__arrow" type="button" aria-label="<?php esc_attr_e( 'Показати наступні категорії', 'kanapka-theme' ); ?>" data-category-strip-next>
			<?php echo kanapka_theme_ui_icon( 'chevron-right', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
		</button>
	<?php endif; ?>
</section>
