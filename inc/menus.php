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

