<?php
/**
 * Catalogue SEO content.
 *
 * @package Kanapka_Theme
 */

$queried_object = get_queried_object();
$is_category    = $queried_object instanceof WP_Term && 'product_cat' === $queried_object->taxonomy;
$title          = $is_category ? $queried_object->name : get_the_title( wc_get_page_id( 'shop' ) );
$content        = '';

if ( $is_category ) {
	$content = term_description( $queried_object );
} else {
	$shop_page_id = wc_get_page_id( 'shop' );
	$content      = $shop_page_id > 0 ? apply_filters( 'the_content', get_post_field( 'post_content', $shop_page_id ) ) : '';
}

if ( ! $content ) {
	return;
}
?>
<section class="catalogue-seo section" aria-labelledby="catalogue-seo-title">
	<div class="catalogue-seo__card container">
		<div class="catalogue-seo__content">
			<h2 id="catalogue-seo-title"><?php echo esc_html( $title ); ?></h2>
			<div class="catalogue-seo__text">
				<?php echo wp_kses_post( $content ); ?>
			</div>
		</div>
	</div>
</section>
