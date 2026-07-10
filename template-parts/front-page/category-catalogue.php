<?php
/**
 * Product category catalogue.
 *
 * @package Kanapka_Theme
 */

$categories = kanapka_theme_get_home_categories( 18 );

if ( ! $categories ) {
	return;
}
?>
<section class="home-section section" aria-labelledby="category-catalogue-title">
	<div class="container">
		<div class="section-heading">
			<h2 id="category-catalogue-title"><?php esc_html_e( 'Product catalogue', 'kanapka-theme' ); ?></h2>
			<a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'View all categories', 'kanapka-theme' ); ?> →</a>
		</div>
		<div class="category-grid">
			<?php
			foreach ( $categories as $category ) {
				get_template_part( 'template-parts/components/category-card', null, array( 'category' => $category ) );
			}
			?>
		</div>
	</div>
</section>

