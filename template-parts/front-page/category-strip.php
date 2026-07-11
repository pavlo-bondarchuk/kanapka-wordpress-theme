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
<section class="category-strip container" aria-label="<?php esc_attr_e( 'Популярні категорії', 'kanapka-theme' ); ?>">
	<div class="category-strip__track">
		<?php
		foreach ( $categories as $category ) {
			get_template_part( 'template-parts/components/category-card', null, array( 'category' => $category, 'compact' => true ) );
		}
		?>
	</div>
</section>
