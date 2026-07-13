<?php
/**
 * Legacy product brands support.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the legacy `brands` taxonomy used by the previous theme.
 */
function kanapka_theme_register_legacy_brands_taxonomy() {
	if ( taxonomy_exists( 'brands' ) ) {
		return;
	}

	register_taxonomy(
		'brands',
		'product',
		array(
			'labels'            => array(
				'name'              => __( 'Brands', 'kanapka-theme' ),
				'singular_name'     => __( 'Brand', 'kanapka-theme' ),
				'search_items'      => __( 'Search brands', 'kanapka-theme' ),
				'all_items'         => __( 'All brands', 'kanapka-theme' ),
				'edit_item'         => __( 'Edit brand', 'kanapka-theme' ),
				'update_item'       => __( 'Update brand', 'kanapka-theme' ),
				'add_new_item'      => __( 'Add brand', 'kanapka-theme' ),
				'new_item_name'     => __( 'New brand name', 'kanapka-theme' ),
				'menu_name'         => __( 'Brands', 'kanapka-theme' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'product-brands' ),
		)
	);
}
add_action( 'init', 'kanapka_theme_register_legacy_brands_taxonomy' );
