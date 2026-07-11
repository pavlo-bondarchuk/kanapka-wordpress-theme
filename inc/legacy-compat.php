<?php
/**
 * Compatibility shims for legacy OXY companion plugins.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'SWPF_THEME_URI' ) ) {
	$legacy_theme_directory = WP_CONTENT_DIR . '/themes/oxy1';
	$legacy_theme_uri       = is_dir( $legacy_theme_directory )
		? content_url( '/themes/oxy1' )
		: get_template_directory_uri();

	define( 'SWPF_THEME_URI', $legacy_theme_uri );
}
