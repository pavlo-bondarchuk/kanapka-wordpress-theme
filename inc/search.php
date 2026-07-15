<?php
/**
 * Product search helpers and live suggestions.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Redirect an empty product search to the catalogue.
 *
 * @return void
 */
function kanapka_theme_redirect_empty_product_search() {
	if ( is_admin() || wp_doing_ajax() ) {
		return;
	}

	$requested_post_type = isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( $_GET['post_type'] ) ) : '';
	$has_search_parameter  = array_key_exists( 's', $_GET );
	$search_term           = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';

	if ( 'product' !== $requested_post_type || ! $has_search_parameter || '' !== trim( $search_term ) ) {
		return;
	}

	$catalogue_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );

	wp_safe_redirect( $catalogue_url );
	exit;
}
add_action( 'template_redirect', 'kanapka_theme_redirect_empty_product_search' );

/**
 * Return matching products for the header live search.
 *
 * @return void
 */
function kanapka_theme_product_search_suggestions() {
	check_ajax_referer( 'kanapka_product_search', 'nonce' );

	$term = isset( $_POST['term'] ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';

	$term_length = function_exists( 'mb_strlen' ) ? mb_strlen( $term ) : strlen( $term );

	if ( $term_length < 2 ) {
		wp_send_json_success( array( 'items' => array() ) );
	}

	$query = new WP_Query(
		array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			's'                   => $term,
			'posts_per_page'      => 6,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	$items = array();

	foreach ( $query->posts as $post ) {
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( $post ) : null;

		if ( ! $product || ! $product->is_visible() ) {
			continue;
		}

		$image_id = $product->get_image_id();
		$image    = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : '';

		$items[] = array(
			'title' => html_entity_decode( wp_strip_all_tags( $product->get_name() ), ENT_QUOTES, get_bloginfo( 'charset' ) ),
			'url'   => $product->get_permalink(),
			'image' => $image ? $image : wc_placeholder_img_src( 'thumbnail' ),
			'price' => wp_strip_all_tags( $product->get_price_html() ),
		);
	}

	wp_reset_postdata();

	wp_send_json_success( array( 'items' => $items ) );
}
add_action( 'wp_ajax_kanapka_product_search_suggestions', 'kanapka_theme_product_search_suggestions' );
add_action( 'wp_ajax_nopriv_kanapka_product_search_suggestions', 'kanapka_theme_product_search_suggestions' );
