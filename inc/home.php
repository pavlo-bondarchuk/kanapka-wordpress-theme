<?php
/**
 * Homepage data providers.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Read a field stored on the WordPress page assigned as the homepage.
 *
 * @param string $name    Field name.
 * @param mixed  $default Fallback value.
 * @return mixed
 */
function kanapka_theme_get_home_field( $name, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}

	$front_page_id = absint( get_option( 'page_on_front' ) );

	if ( ! $front_page_id ) {
		return $default;
	}

	$value = get_field( $name, $front_page_id );

	return null === $value || false === $value || '' === $value ? $default : $value;
}

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

/**
 * Return normalized homepage hero slides with a safe legacy fallback.
 *
 * @return array
 */
function kanapka_theme_get_home_hero_slides() {
	$rows   = kanapka_theme_get_home_field( 'kanapka_home_hero_slides', array() );
	$slides = array();

	if ( is_array( $rows ) ) {
		foreach ( $rows as $row ) {
			$image_id = absint( $row['image'] ?? 0 );

			if ( ! $image_id ) {
				continue;
			}

			$slides[] = array(
				'image_id'    => $image_id,
				'title'       => sanitize_text_field( $row['title'] ?? '' ),
				'text'        => wp_kses_post( $row['text'] ?? '' ),
				'button_label' => sanitize_text_field( $row['button_label'] ?? '' ),
				'button_url'   => esc_url_raw( $row['button_url'] ?? '' ),
			);
		}
	}

	if ( $slides ) {
		return $slides;
	}

	return array(
		array(
			'image_id'    => kanapka_theme_get_hero_image_id(),
			'title'       => __( 'Ready-made sets for every celebration', 'kanapka-theme' ),
			'text'        => __( 'Canapés, appetizers and buffet sets made from fresh ingredients and delivered at the right time.', 'kanapka-theme' ),
			'button_label' => __( 'Browse catalogue', 'kanapka-theme' ),
			'button_url'   => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ),
		),
	);
}

/**
 * Return the shared hero benefit row.
 *
 * @return array
 */
function kanapka_theme_get_home_hero_benefits() {
	$rows     = kanapka_theme_get_home_field( 'kanapka_home_hero_benefits', array() );
	$benefits = array();
	$icons    = array( 'delivery', 'clock', 'leaf' );

	if ( is_array( $rows ) ) {
		foreach ( array_slice( $rows, 0, 3 ) as $row ) {
			$icon = sanitize_key( $row['icon'] ?? '' );

			$benefits[] = array(
				'icon'  => in_array( $icon, $icons, true ) ? $icon : 'delivery',
				'title' => sanitize_text_field( $row['title'] ?? '' ),
				'text'  => sanitize_text_field( $row['text'] ?? '' ),
			);
		}
	}

	if ( $benefits ) {
		return $benefits;
	}

	return array(
		array(
			'icon'  => 'delivery',
			'title' => __( 'Free delivery', 'kanapka-theme' ),
			'text'  => __( 'For qualifying Kyiv orders', 'kanapka-theme' ),
		),
		array(
			'icon'  => 'clock',
			'title' => __( 'Fast delivery', 'kanapka-theme' ),
			'text'  => __( 'On the order day', 'kanapka-theme' ),
		),
		array(
			'icon'  => 'leaf',
			'title' => __( 'Fresh products', 'kanapka-theme' ),
			'text'  => __( 'Premium quality', 'kanapka-theme' ),
		),
	);
}
