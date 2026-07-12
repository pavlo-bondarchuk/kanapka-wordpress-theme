<?php
/**
 * Catalogue hero.
 *
 * @package Kanapka_Theme
 */

$queried_object = get_queried_object();
$is_category    = $queried_object instanceof WP_Term && 'product_cat' === $queried_object->taxonomy;
$title          = $is_category ? $queried_object->name : __( 'Каталог продукції', 'kanapka-theme' );
$description    = $is_category ? term_description( $queried_object ) : get_the_excerpt( wc_get_page_id( 'shop' ) );
$hero_image_id  = $is_category ? absint( get_term_meta( $queried_object->term_id, 'thumbnail_id', true ) ) : 0;

if ( ! $hero_image_id ) {
	$categories = kanapka_theme_get_shop_categories( 1 );
	if ( $categories ) {
		$hero_image_id = absint( get_term_meta( $categories[0]->term_id, 'thumbnail_id', true ) );
	}
}
?>
<section class="catalogue-hero container" aria-labelledby="catalogue-title">
	<div class="catalogue-hero__content">
		<?php woocommerce_breadcrumb(); ?>
		<h1 id="catalogue-title"><?php echo esc_html( $title ); ?></h1>
		<?php if ( $description ) : ?>
			<div class="catalogue-hero__text"><?php echo wp_kses_post( wpautop( wp_strip_all_tags( $description ) ) ); ?></div>
		<?php else : ?>
			<p><?php esc_html_e( 'Свіжі та смачні страви для будь-якої події. Обирайте серед десятків категорій.', 'kanapka-theme' ); ?></p>
		<?php endif; ?>
	</div>
	<?php if ( $hero_image_id ) : ?>
		<figure class="catalogue-hero__media">
			<?php echo wp_get_attachment_image( $hero_image_id, 'large', false, array( 'alt' => $title, 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(max-width: 900px) 100vw, 48vw' ) ); ?>
		</figure>
	<?php endif; ?>
</section>
