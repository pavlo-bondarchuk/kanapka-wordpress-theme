<?php
/**
 * Optional Secure Custom Fields integration.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the JSON synchronization directory when SCF is available.
 *
 * @param string $path Existing JSON save path.
 * @return string
 */
function kanapka_theme_scf_json_save_path( $path ) {
	unset( $path );

	return get_theme_file_path( '/acf-json' );
}
add_filter( 'acf/settings/save_json', 'kanapka_theme_scf_json_save_path' );

/**
 * Add the theme JSON directory to load paths.
 *
 * @param array $paths Existing JSON paths.
 * @return array
 */
function kanapka_theme_scf_json_load_paths( $paths ) {
	$paths[] = get_theme_file_path( '/acf-json' );

	return array_unique( $paths );
}
add_filter( 'acf/settings/load_json', 'kanapka_theme_scf_json_load_paths' );

