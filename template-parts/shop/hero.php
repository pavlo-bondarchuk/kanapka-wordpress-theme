<?php
/**
 * Catalogue hero.
 *
 * @package Kanapka_Theme
 */

$queried_object = get_queried_object();
$is_category    = $queried_object instanceof WP_Term && 'product_cat' === $queried_object->taxonomy;
$shop_page_id   = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : 0;
$title = $is_category ? $queried_object->name : __( 'Product catalogue', 'kanapka-theme' );
$description    = $is_category ? kanapka_theme_get_term_meta_description( $queried_object ) : kanapka_theme_get_post_meta_description( $shop_page_id );
$hero_image_id  = $is_category ? absint( get_term_meta( $queried_object->term_id, 'thumbnail_id', true ) ) : absint( get_post_thumbnail_id( $shop_page_id ) );

if ( ! $hero_image_id ) {
	$categories = kanapka_theme_get_shop_categories( 1 );
	if ( $categories ) {
		$hero_image_id = absint( get_term_meta( $categories[0]->term_id, 'thumbnail_id', true ) );
	}
}

$description = wp_trim_words( wp_strip_all_tags( $description ), 24, '' );
$description = $description ? $description : __( 'Fresh dishes for buffets, office events and celebrations. Choose a category and order online.', 'kanapka-theme' );
?>
<section class="catalogue-hero" aria-labelledby="catalogue-title">
	<?php if ( $hero_image_id ) : ?>
		<figure class="catalogue-hero__media">
			<?php echo wp_get_attachment_image( $hero_image_id, 'large', false, array( 'alt' => '', 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '100vw' ) ); ?>
		</figure>
	<?php endif; ?>
	<div class="catalogue-hero__content container">
		<?php kanapka_theme_breadcrumb(); ?>
		<h1 id="catalogue-title"><?php echo esc_html( $title ); ?></h1>
		<?php if ( $description ) : ?>
			<p><?php echo esc_html( $description ); ?></p>
		<?php else : ?>
			<p><?php esc_html_e( 'Fresh and delicious dishes for every event. Choose from dozens of categories.', 'kanapka-theme' ); ?></p>
		<?php endif; ?>
	</div>
</section>
