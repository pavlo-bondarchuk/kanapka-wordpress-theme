<?php
/**
 * Theme bootstrap.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

$kanapka_theme_modules = array(
	'legacy-compat',
	'icons',
	'template-tags',
	'setup',
	'assets',
	'menus',
	'images',
	'search',
	'brands',
	'home',
	'shop',
	'accessibility',
	'seo',
	'woocommerce',
	'reviews',
	'scf',
	'analytics',
);

foreach ( $kanapka_theme_modules as $kanapka_theme_module ) {
	require_once get_theme_file_path( '/inc/' . $kanapka_theme_module . '.php' );
}
