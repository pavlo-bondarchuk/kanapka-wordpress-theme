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
			'top-bar'  => __( 'Top bar', 'kanapka-theme' ),
			'footer-1' => __( 'First footer column', 'kanapka-theme' ),
			'footer-2' => __( 'Second footer column', 'kanapka-theme' ),
			'footer-3' => __( 'Third footer column', 'kanapka-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'kanapka_theme_register_menus' );

/**
 * Remove the legacy Weglot placeholder from the old top menu.
 *
 * The current theme renders its language switcher beside the menu so it stays
 * available even when the legacy placeholder integration is not active.
 *
 * @param WP_Post[] $items Menu items.
 * @param stdClass  $args  Menu arguments.
 * @return WP_Post[]
 */
function kanapka_theme_remove_legacy_top_bar_language_item( $items, $args ) {
	if ( empty( $args->theme_location ) || 'top-bar' !== $args->theme_location ) {
		return $items;
	}

	return array_values(
		array_filter(
			$items,
			static function ( $item ) {
				$url     = isset( $item->url ) ? strtolower( trim( $item->url ) ) : '';
				$classes = isset( $item->classes ) ? (array) $item->classes : array();
				$item_id = isset( $item->ID ) ? (string) $item->ID : '';

				return '#weglot_switcher' !== $url
					&& ! in_array( 'menu-item-weglot', $classes, true )
					&& 0 !== strpos( $item_id, 'weglot-' );
			}
		)
	);
}
add_filter( 'wp_nav_menu_objects', 'kanapka_theme_remove_legacy_top_bar_language_item', 10, 2 );

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

		if ( 0 === $depth && kanapka_theme_menu_item_has_contact_menu( $item ) ) {
			$output .= kanapka_theme_get_contact_mega_menu();
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
 * Determine whether a menu item owns the configured contact panel.
 *
 * @param WP_Post $item Menu item.
 * @return bool
 */
function kanapka_theme_menu_item_has_contact_menu( $item ) {
	if ( ! kanapka_theme_get_option( 'kanapka_contact_menu_enabled', false ) ) {
		return false;
	}

	$parent_page = absint( kanapka_theme_get_option( 'kanapka_contact_menu_parent_page', 0 ) );

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

	if ( kanapka_theme_menu_item_has_contact_menu( $item ) ) {
		$classes[] = 'menu-item-has-contact-menu';
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

/**
 * Build the contact panel from SCF header settings.
 *
 * @return string
 */
function kanapka_theme_get_contact_mega_menu() {
	ob_start();
	get_template_part( 'template-parts/header/contact-mega-menu' );

	return (string) ob_get_clean();
}
