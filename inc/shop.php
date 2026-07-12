<?php
/**
 * Shop archive helpers.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Determine whether the current request is a product catalogue archive.
 *
 * @return bool
 */
function kanapka_theme_is_catalogue_archive() {
	return function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() );
}

/**
 * Get top-level product categories for catalogue navigation.
 *
 * @param int $limit Maximum number of categories, 0 for all.
 * @return WP_Term[]
 */
function kanapka_theme_get_shop_categories( $limit = 0 ) {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return array();
	}

	$categories = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'parent'     => 0,
			'orderby'    => 'menu_order',
			'order'      => 'ASC',
			'number'     => absint( $limit ),
		)
	);

	return is_wp_error( $categories ) ? array() : $categories;
}

/**
 * Get latest purchasable products for the catalogue sidebar.
 *
 * @param int $limit Product limit.
 * @return WC_Product[]
 */
function kanapka_theme_get_shop_new_products( $limit = 4 ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}

	return wc_get_products(
		array(
			'status'     => 'publish',
			'limit'      => absint( $limit ),
			'orderby'    => 'date',
			'order'      => 'DESC',
			'visibility' => 'visible',
		)
	);
}

/**
 * Apply a simple GET-based price filter to product archives.
 *
 * @param WP_Query $query Main query.
 */
function kanapka_theme_apply_catalogue_price_filter( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! function_exists( 'is_shop' ) ) {
		return;
	}

	if ( ! ( is_shop() || is_product_taxonomy() ) ) {
		return;
	}

	$min_price = isset( $_GET['min_price'] ) ? wc_format_decimal( wp_unslash( $_GET['min_price'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$max_price = isset( $_GET['max_price'] ) ? wc_format_decimal( wp_unslash( $_GET['max_price'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( '' === $min_price && '' === $max_price ) {
		return;
	}

	$meta_query = (array) $query->get( 'meta_query' );
	$price_rule = array(
		'key'     => '_price',
		'type'    => 'DECIMAL',
		'compare' => 'BETWEEN',
		'value'   => array(
			'' === $min_price ? 0 : (float) $min_price,
			'' === $max_price ? PHP_INT_MAX : (float) $max_price,
		),
	);

	$meta_query[] = $price_rule;
	$query->set( 'meta_query', $meta_query );
}
add_action( 'pre_get_posts', 'kanapka_theme_apply_catalogue_price_filter' );
