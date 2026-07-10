<?php
/**
 * Homepage data providers.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get visible product categories for the homepage.
 *
 * @param int $limit Maximum number of categories.
 * @return array
 */
function kanapka_theme_get_home_categories( $limit = 18 ) {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return array();
	}

	$categories = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'parent'     => 0,
			'number'     => absint( $limit ),
			'orderby'    => 'menu_order',
			'order'      => 'ASC',
		)
	);

	return is_wp_error( $categories ) ? array() : $categories;
}

/**
 * Get popular products using WooCommerce's visibility rules.
 *
 * @param int $limit Maximum number of products.
 * @return array
 */
function kanapka_theme_get_popular_products( $limit = 5 ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}

	return wc_get_products(
		array(
			'status'  => 'publish',
			'limit'   => absint( $limit ),
			'orderby' => 'popularity',
			'order'   => 'DESC',
			'return'  => 'objects',
		)
	);
}

/**
 * Resolve an image for the hero without introducing a custom field.
 *
 * @return int
 */
function kanapka_theme_get_hero_image_id() {
	$front_page_id = (int) get_option( 'page_on_front' );
	$image_id      = $front_page_id ? get_post_thumbnail_id( $front_page_id ) : 0;

	if ( $image_id ) {
		return (int) $image_id;
	}

	$products = kanapka_theme_get_popular_products( 1 );

	return $products ? (int) $products[0]->get_image_id() : 0;
}

