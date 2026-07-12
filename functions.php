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
	'setup',
	'assets',
	'menus',
	'images',
	'brands',
	'home',
	'shop',
	'accessibility',
	'seo',
	'woocommerce',
	'scf',
);

foreach ( $kanapka_theme_modules as $kanapka_theme_module ) {
	require_once get_theme_file_path( '/inc/' . $kanapka_theme_module . '.php' );
}
