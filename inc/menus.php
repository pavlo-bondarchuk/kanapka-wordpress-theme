<?php
/**
 * Navigation areas.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme menu locations.
 */
function kanapka_theme_register_menus() {
	register_nav_menus(
		array(
			'primary'  => __( 'Primary navigation', 'kanapka-theme' ),
			'footer-1' => __( 'Footer column one', 'kanapka-theme' ),
			'footer-2' => __( 'Footer column two', 'kanapka-theme' ),
			'footer-3' => __( 'Footer column three', 'kanapka-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'kanapka_theme_register_menus' );

/**
 * Render the standard WordPress submenu and append an optional category mega-menu.
 */
class Kanapka_Theme_Nav_Walker extends Walker_Nav_Menu {
	/**
	 * Finish a menu item and inject the configured mega-menu before its closing tag.
	 *
	 * @param string   $output Menu output.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Menu depth.
	 * @param stdClass $args   Menu arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( 0 === $depth && kanapka_theme_menu_item_has_mega_menu( $item ) ) {
			$output .= kanapka_theme_get_category_mega_menu();
		}

		parent::end_el( $output, $item, $depth, $args );
	}
}

/**
 * Determine whether a menu item owns the configured category mega-menu.
 *
 * @param WP_Post $item Menu item.
 * @return bool
 */
function kanapka_theme_menu_item_has_mega_menu( $item ) {
	if ( ! kanapka_theme_get_option( 'kanapka_mega_menu_enabled', false ) ) {
		return false;
	}

	$parent_page = absint( kanapka_theme_get_option( 'kanapka_mega_menu_parent_page', 0 ) );

	return $parent_page && $parent_page === absint( $item->object_id );
}

/**
 * Add a stable CSS class to the menu item that owns the mega-menu.
 *
 * @param array   $classes Menu item classes.
 * @param WP_Post $item    Menu item.
 * @return array
 */
function kanapka_theme_mega_menu_item_class( $classes, $item ) {
	if ( kanapka_theme_menu_item_has_mega_menu( $item ) ) {
		$classes[] = 'menu-item-has-mega-menu';
	}

	return array_unique( $classes );
}
add_filter( 'nav_menu_css_class', 'kanapka_theme_mega_menu_item_class', 10, 2 );

/**
 * Build category mega-menu markup from live WooCommerce taxonomy data.
 *
 * @return string
 */
function kanapka_theme_get_category_mega_menu() {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return '';
	}

	$parent_id  = absint( kanapka_theme_get_option( 'kanapka_mega_menu_base_category', 0 ) );
	$orderby    = kanapka_theme_get_option( 'kanapka_mega_menu_orderby', 'menu_order' );
	$order      = kanapka_theme_get_option( 'kanapka_mega_menu_order', 'ASC' );
	$show_image = (bool) kanapka_theme_get_option( 'kanapka_mega_menu_show_images', false );
	$child_max  = absint( kanapka_theme_get_option( 'kanapka_mega_menu_child_limit', 0 ) );
	$categories = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'parent'     => $parent_id,
			'orderby'    => in_array( $orderby, array( 'name', 'term_id', 'count', 'slug', 'menu_order' ), true ) ? $orderby : 'menu_order',
			'order'      => 'DESC' === $order ? 'DESC' : 'ASC',
		)
	);

	if ( is_wp_error( $categories ) || ! $categories ) {
		return '';
	}

	ob_start();
	get_template_part(
		'template-parts/header/category-mega-menu',
		null,
		array(
			'categories' => $categories,
			'show_image' => $show_image,
			'child_max'  => $child_max,
		)
	);

	return (string) ob_get_clean();
}
