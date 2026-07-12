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
 * Get all product categories in parent-first display order.
 *
 * @return WP_Term[]
 */
function kanapka_theme_get_shop_category_tree() {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return array();
	}

	$categories = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'orderby'    => 'menu_order',
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $categories ) ) {
		return array();
	}

	$children_by_parent = array();

	foreach ( $categories as $category ) {
		$children_by_parent[ $category->parent ][] = $category;
	}

	$ordered_categories = array();
	$append_children     = static function ( $parent_id, $depth ) use ( &$append_children, &$children_by_parent, &$ordered_categories ) {
		foreach ( $children_by_parent[ $parent_id ] ?? array() as $category ) {
			$category->kanapka_depth = $depth;
			$ordered_categories[]    = $category;
			$append_children( $category->term_id, $depth + 1 );
		}
	};

	$append_children( 0, 0 );

	return $ordered_categories;
}

/**
 * Get direct child categories for a product category term.
 *
 * @param int $parent_id Parent product category ID.
 * @return WP_Term[]
 */
function kanapka_theme_get_shop_child_categories( $parent_id ) {
	$parent_id = absint( $parent_id );

	if ( ! $parent_id || ! taxonomy_exists( 'product_cat' ) ) {
		return array();
	}

	$categories = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'parent'     => $parent_id,
			'orderby'    => 'menu_order',
			'order'      => 'ASC',
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
 * Get an SEO meta description from common SEO plugins.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function kanapka_theme_get_post_meta_description( $post_id ) {
	$post_id = absint( $post_id );

	if ( ! $post_id ) {
		return '';
	}

	$meta_keys = array(
		'_yoast_wpseo_metadesc',
		'rank_math_description',
		'_aioseo_description',
	);

	foreach ( $meta_keys as $meta_key ) {
		$description = get_post_meta( $post_id, $meta_key, true );

		if ( $description ) {
			return trim( wp_strip_all_tags( $description ) );
		}
	}

	return '';
}

/**
 * Get a product category SEO meta description.
 *
 * @param WP_Term $term Product category term.
 * @return string
 */
function kanapka_theme_get_term_meta_description( $term ) {
	if ( ! $term instanceof WP_Term ) {
		return '';
	}

	$yoast_taxonomy_meta = get_option( 'wpseo_taxonomy_meta', array() );
	$yoast_description   = $yoast_taxonomy_meta[ $term->taxonomy ][ $term->term_id ]['wpseo_desc'] ?? '';

	if ( $yoast_description ) {
		return trim( wp_strip_all_tags( $yoast_description ) );
	}

	$term_meta_keys = array(
		'rank_math_description',
		'_aioseo_description',
	);

	foreach ( $term_meta_keys as $meta_key ) {
		$description = get_term_meta( $term->term_id, $meta_key, true );

		if ( $description ) {
			return trim( wp_strip_all_tags( $description ) );
		}
	}

	return '';
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
