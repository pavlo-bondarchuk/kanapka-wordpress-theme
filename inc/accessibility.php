<?php
/**
 * Accessibility helpers.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add a descriptive label to the primary navigation.
 *
 * @param array  $attributes Navigation attributes.
 * @param object $args       Menu arguments.
 * @return array
 */
function kanapka_theme_nav_attributes( $attributes, $args ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
		$attributes['aria-label'] = __( 'Primary navigation', 'kanapka-theme' );
	}

	return $attributes;
}
add_filter( 'nav_menu_item_attributes', 'kanapka_theme_nav_attributes', 10, 2 );
