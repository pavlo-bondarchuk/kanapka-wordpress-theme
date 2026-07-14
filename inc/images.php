<?php
/**
 * Responsive image configuration.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register image crops used by the design.
 */
function kanapka_theme_register_image_sizes() {
	add_image_size( 'kanapka-hero', 1920, 900, true );
	add_image_size( 'kanapka-hero-mobile', 414, 640, true );
	add_image_size( 'kanapka-hero-mobile-retina', 582, 900, true );
	add_image_size( 'kanapka-category', 480, 360, true );
	add_image_size( 'kanapka-mega-menu', 48, 48, true );
	add_image_size( 'kanapka-product-card', 640, 480, true );
	add_image_size( 'kanapka-service', 760, 420, true );
	add_image_size( 'kanapka-catering-gallery', 640, 460, true );
}
add_action( 'after_setup_theme', 'kanapka_theme_register_image_sizes' );
