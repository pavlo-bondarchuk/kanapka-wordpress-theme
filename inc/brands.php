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
				'name'              => __( 'Бренди', 'kanapka-theme' ),
				'singular_name'     => __( 'Бренд', 'kanapka-theme' ),
				'search_items'      => __( 'Шукати бренди', 'kanapka-theme' ),
				'all_items'         => __( 'Усі бренди', 'kanapka-theme' ),
				'edit_item'         => __( 'Редагувати бренд', 'kanapka-theme' ),
				'update_item'       => __( 'Оновити бренд', 'kanapka-theme' ),
				'add_new_item'      => __( 'Додати бренд', 'kanapka-theme' ),
				'new_item_name'     => __( 'Назва нового бренду', 'kanapka-theme' ),
				'menu_name'         => __( 'Бренди', 'kanapka-theme' ),
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
