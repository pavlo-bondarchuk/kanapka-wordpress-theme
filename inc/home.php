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
 * Homepage settings are only applied when explicitly requested so other
 * recommendation blocks can keep using the automatic popularity query.
 *
 * @param int  $limit             Maximum number of products.
 * @param bool $use_home_settings Whether to use the homepage product source.
 * @return array
 */
function kanapka_theme_get_popular_products( $limit = 5, $use_home_settings = false ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}

	$limit = absint( $limit );

	if ( ! $limit ) {
		return array();
	}

	if ( $use_home_settings ) {
		$front_page_id = absint( get_option( 'page_on_front' ) );

		if ( $front_page_id && function_exists( 'get_field' ) ) {
			$enabled = metadata_exists( 'post', $front_page_id, 'kanapka_home_popular_enabled' )
				? (bool) get_field( 'kanapka_home_popular_enabled', $front_page_id )
				: true;

			if ( ! $enabled ) {
				return array();
			}

			$source = (string) get_field( 'kanapka_home_popular_source', $front_page_id );

			if ( 'manual' === $source ) {
				$selected = get_field( 'kanapka_home_popular_products', $front_page_id );
				$products = array();

				foreach ( is_array( $selected ) ? $selected : array() as $selected_product ) {
					if ( $selected_product instanceof WC_Product ) {
						$product = $selected_product;
					} else {
						$product_id = $selected_product instanceof WP_Post
							? $selected_product->ID
							: absint( $selected_product );
						$product    = wc_get_product( $product_id );
					}

					if ( ! $product || 'publish' !== $product->get_status() || ! $product->is_visible() ) {
						continue;
					}

					$products[] = $product;

					if ( count( $products ) >= $limit ) {
						break;
					}
				}

				return $products;
			}
		}
	}

	return wc_get_products(
		array(
			'status'  => 'publish',
			'limit'   => $limit,
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
			$button   = is_array( $row['button'] ?? null ) ? $row['button'] : array();

			if ( ! $image_id ) {
				continue;
			}

			$slides[] = array(
				'image_id'      => $image_id,
				'title'         => sanitize_text_field( $row['title'] ?? '' ),
				'text'          => wp_kses_post( $row['text'] ?? '' ),
				'button_label'  => sanitize_text_field( $button['title'] ?? ( $row['button_label'] ?? '' ) ),
				'button_url'    => esc_url_raw( $button['url'] ?? ( $row['button_url'] ?? '' ) ),
				'button_target' => '_blank' === ( $button['target'] ?? '' ) ? '_blank' : '_self',
			);
		}
	}

	if ( $slides ) {
		return $slides;
	}

	return array(
		array(
			'image_id'      => kanapka_theme_get_hero_image_id(),
			'title'         => __( 'Ready-made sets for every celebration', 'kanapka-theme' ),
			'text'          => __( 'Canapés, appetizers and buffet sets made from fresh ingredients and delivered at the right time.', 'kanapka-theme' ),
			'button_label'  => __( 'Browse catalogue', 'kanapka-theme' ),
			'button_url'    => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ),
			'button_target' => '_self',
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
			'text'  => __( 'For orders within Kyiv', 'kanapka-theme' ),
		),
		array(
			'icon'  => 'clock',
			'title' => __( 'Fast delivery', 'kanapka-theme' ),
			'text'  => __( 'On the day of your order', 'kanapka-theme' ),
		),
		array(
			'icon'  => 'leaf',
			'title' => __( 'Fresh ingredients', 'kanapka-theme' ),
			'text'  => __( 'Premium quality', 'kanapka-theme' ),
		),
	);
}

/**
 * Return the homepage SEO section content.
 *
 * @return array
 */
function kanapka_theme_get_home_seo_section() {
	$title    = sanitize_text_field( kanapka_theme_get_home_field( 'kanapka_home_seo_title', '' ) );
	$text     = wp_kses_post( kanapka_theme_get_home_field( 'kanapka_home_seo_text', '' ) );
	$image_id = absint( kanapka_theme_get_home_field( 'kanapka_home_seo_image', 0 ) );
	$rows     = kanapka_theme_get_home_field( 'kanapka_home_seo_benefits', array() );
	$benefits = array();
	$icons    = array( 'leaf', 'users', 'delivery', 'clock', 'briefcase', 'sparkles' );

	if ( is_array( $rows ) ) {
		foreach ( $rows as $row ) {
			$icon          = sanitize_key( $row['icon'] ?? '' );
			$benefit_title = sanitize_text_field( $row['title'] ?? '' );
			$benefit_text  = sanitize_text_field( $row['text'] ?? '' );

			if ( ! $benefit_title && ! $benefit_text ) {
				continue;
			}

			$benefits[] = array(
				'icon'  => in_array( $icon, $icons, true ) ? $icon : 'sparkles',
				'title' => $benefit_title,
				'text'  => $benefit_text,
			);
		}
	}

	if ( $title || $text || $image_id || $benefits ) {
		return compact( 'title', 'text', 'image_id', 'benefits' );
	}

	return array(
		'title'    => __( 'Canapé, appetizer and buffet set delivery in Kyiv', 'kanapka-theme' ),
		'text'     => __( 'Order ready-made sets for an office meeting, birthday, buffet or family celebration. We prepare dishes shortly before dispatch and deliver them in presentable packaging.', 'kanapka-theme' ),
		'image_id' => kanapka_theme_get_hero_image_id(),
		'benefits' => array(
			array(
				'icon'  => 'leaf',
				'title' => __( 'Only fresh ingredients', 'kanapka-theme' ),
				'text'  => '',
			),
			array(
				'icon'  => 'users',
				'title' => __( 'Personal approach', 'kanapka-theme' ),
				'text'  => '',
			),
			array(
				'icon'  => 'delivery',
				'title' => __( 'On-time delivery', 'kanapka-theme' ),
				'text'  => '',
			),
			array(
				'icon'  => 'sparkles',
				'title' => __( 'Professional team', 'kanapka-theme' ),
				'text'  => '',
			),
			array(
				'icon'  => 'briefcase',
				'title' => __( 'Wide selection and competitive prices', 'kanapka-theme' ),
				'text'  => '',
			),
		),
	);
}

/**
 * Return homepage turnkey service cards.
 *
 * @return array
 */
function kanapka_theme_get_home_services() {
	$rows     = kanapka_theme_get_home_field( 'kanapka_home_services', array() );
	$services = array();

	if ( ! is_array( $rows ) ) {
		return $services;
	}

	foreach ( $rows as $row ) {
		$title    = sanitize_text_field( $row['title'] ?? '' );
		$text     = sanitize_text_field( $row['text'] ?? '' );
		$button   = is_array( $row['button'] ?? null ) ? $row['button'] : array();
		$image_id = absint( $row['image'] ?? 0 );

		if ( ! $title && ! $text && ! $button && ! $image_id ) {
			continue;
		}

		$services[] = array(
			'title'         => $title,
			'text'          => $text,
			'button_label'  => sanitize_text_field( $button['title'] ?? '' ),
			'button_url'    => esc_url_raw( $button['url'] ?? '' ),
			'button_target' => '_blank' === ( $button['target'] ?? '' ) ? '_blank' : '_self',
			'image_id'      => $image_id,
		);
	}

	return $services;
}

/**
 * Return homepage order benefit cards.
 *
 * @return array
 */
function kanapka_theme_get_home_order_benefits() {
	$title  = sanitize_text_field( kanapka_theme_get_home_field( 'kanapka_home_benefits_title', '' ) );
	$rows   = kanapka_theme_get_home_field( 'kanapka_home_benefits_items', array() );
	$items  = array();
	$icons  = array( 'leaf', 'utensils', 'delivery', 'percent', 'star', 'cart', 'users', 'sparkles' );
	$colors = array( 'green', 'red', 'blue', 'orange', 'sky' );
	$default_sequence = array(
		array( 'icon' => 'leaf', 'color' => 'green' ),
		array( 'icon' => 'utensils', 'color' => 'red' ),
		array( 'icon' => 'delivery', 'color' => 'sky' ),
		array( 'icon' => 'percent', 'color' => 'orange' ),
		array( 'icon' => 'star', 'color' => 'red' ),
		array( 'icon' => 'cart', 'color' => 'blue' ),
	);

	if ( is_array( $rows ) ) {
		foreach ( $rows as $index => $row ) {
			$item_title = sanitize_text_field( $row['title'] ?? '' );
			$item_text  = sanitize_text_field( $row['text'] ?? '' );
			$icon       = sanitize_key( $row['icon'] ?? '' );
			$color      = sanitize_key( $row['color'] ?? '' );
			$defaults   = $default_sequence[ $index % count( $default_sequence ) ];

			if ( ! $item_title && ! $item_text ) {
				continue;
			}

			$items[] = array(
				'icon'  => in_array( $icon, $icons, true ) ? $icon : $defaults['icon'],
				'color' => in_array( $color, $colors, true ) ? $color : $defaults['color'],
				'title' => $item_title,
				'text'  => $item_text,
			);
		}
	}

	if ( $title || $items ) {
		return compact( 'title', 'items' );
	}

	return array(
		'title' => __( 'Discover the benefits of ordering office buffet catering from us!', 'kanapka-theme' ),
		'items' => array(
			array(
				'icon'  => 'leaf',
				'color' => 'green',
				'title' => __( 'Fresh ingredients', 'kanapka-theme' ),
				'text'  => __( 'We use only fresh, high-quality ingredients', 'kanapka-theme' ),
			),
			array(
				'icon'  => 'utensils',
				'color' => 'red',
				'title' => __( 'Impressive selection', 'kanapka-theme' ),
				'text'  => __( 'A wide selection of canapés, appetizers and sets', 'kanapka-theme' ),
			),
			array(
				'icon'  => 'delivery',
				'color' => 'sky',
				'title' => __( 'Free delivery on orders over UAH 5,000', 'kanapka-theme' ),
				'text'  => __( 'Across Kyiv and the region at a convenient time', 'kanapka-theme' ),
			),
			array(
				'icon'  => 'percent',
				'color' => 'orange',
				'title' => __( '10% discount for collection', 'kanapka-theme' ),
				'text'  => __( 'Collect your order and save', 'kanapka-theme' ),
			),
			array(
				'icon'  => 'star',
				'color' => 'red',
				'title' => __( '18 years on the market', 'kanapka-theme' ),
				'text'  => __( 'Experience trusted by customers', 'kanapka-theme' ),
			),
			array(
				'icon'  => 'cart',
				'color' => 'blue',
				'title' => __( 'Easy online ordering', 'kanapka-theme' ),
				'text'  => __( 'Place your order online in just a few clicks', 'kanapka-theme' ),
			),
		),
	);
}

/**
 * Resolve a legacy brand thumbnail attachment ID.
 *
 * @param int $term_id Brand term ID.
 * @return int
 */
function kanapka_theme_get_brand_logo_id( $term_id ) {
	$term_id = absint( $term_id );

	if ( ! $term_id ) {
		return 0;
	}

	if ( function_exists( 'wcm_sds_brands_thumbnail_id' ) ) {
		return absint( wcm_sds_brands_thumbnail_id( $term_id ) );
	}

	$meta_keys = array( 'thumbnail_id', 'brand_thumbnail_id', 'image' );

	foreach ( $meta_keys as $meta_key ) {
		$image_id = absint( get_term_meta( $term_id, $meta_key, true ) );

		if ( $image_id ) {
			return $image_id;
		}
	}

	global $wpdb;

	$table = $wpdb->prefix . 'wcm_sds_brands';

	if ( $table !== $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) ) { // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		return 0;
	}

	return absint(
		$wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->prepare(
				"SELECT wsb.meta_value
				FROM {$wpdb->prefix}wcm_sds_brands wsb
				LEFT JOIN {$wpdb->term_taxonomy} tt ON wsb.term_taxonomy_id = tt.term_taxonomy_id
				WHERE tt.term_id = %d
				AND tt.taxonomy = 'brands'
				AND wsb.meta_key = 'thumbnail_id'
				LIMIT 1",
				$term_id
			)
		)
	);
}

/**
 * Return visible homepage client brand logos.
 *
 * @param int $limit Maximum number of brands.
 * @return array
 */
function kanapka_theme_get_home_client_brands( $limit = 24 ) {
	if ( ! taxonomy_exists( 'brands' ) ) {
		return array();
	}

	$terms = get_terms(
		array(
			'taxonomy'   => 'brands',
			'hide_empty' => false,
			'number'     => absint( $limit ),
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $terms ) || ! $terms ) {
		return array();
	}

	$brands = array();

	foreach ( $terms as $term ) {
		$image_id = kanapka_theme_get_brand_logo_id( $term->term_id );

		if ( ! $image_id ) {
			continue;
		}

		$link = get_term_link( $term, 'brands' );

		if ( is_wp_error( $link ) ) {
			$link = '';
		}

		$brands[] = array(
			'name'     => $term->name,
			'url'      => $link,
			'image_id' => $image_id,
		);
	}

	return $brands;
}
