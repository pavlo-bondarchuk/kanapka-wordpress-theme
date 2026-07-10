<?php
/**
 * Asset loading.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return a cache-safe asset version.
 *
 * @param string $relative_path Theme-relative file path.
 * @return string
 */
function kanapka_theme_asset_version( $relative_path ) {
	$file = get_theme_file_path( $relative_path );

	return file_exists( $file ) ? (string) filemtime( $file ) : wp_get_theme()->get( 'Version' );
}

/**
 * Enqueue only the assets required by the current view.
 */
function kanapka_theme_enqueue_assets() {
	wp_enqueue_style(
		'kanapka-theme-main',
		get_theme_file_uri( '/assets/css/main.css' ),
		array(),
		kanapka_theme_asset_version( '/assets/css/main.css' )
	);

	wp_enqueue_style(
		'kanapka-theme-header',
		get_theme_file_uri( '/assets/css/components/header.css' ),
		array( 'kanapka-theme-main' ),
		kanapka_theme_asset_version( '/assets/css/components/header.css' )
	);

	wp_enqueue_style(
		'kanapka-theme-footer',
		get_theme_file_uri( '/assets/css/components/footer.css' ),
		array( 'kanapka-theme-main' ),
		kanapka_theme_asset_version( '/assets/css/components/footer.css' )
	);

	wp_enqueue_script(
		'kanapka-theme-navigation',
		get_theme_file_uri( '/assets/js/components/navigation.js' ),
		array(),
		kanapka_theme_asset_version( '/assets/js/components/navigation.js' ),
		true
	);

	wp_enqueue_script(
		'kanapka-theme-mega-menu',
		get_theme_file_uri( '/assets/js/components/mega-menu.js' ),
		array(),
		kanapka_theme_asset_version( '/assets/js/components/mega-menu.js' ),
		true
	);

	wp_enqueue_script(
		'kanapka-theme-header-actions',
		get_theme_file_uri( '/assets/js/components/header-actions.js' ),
		array(),
		kanapka_theme_asset_version( '/assets/js/components/header-actions.js' ),
		true
	);

	if ( is_front_page() ) {
		wp_enqueue_style(
			'kanapka-theme-front-page',
			get_theme_file_uri( '/assets/css/pages/front-page.css' ),
			array( 'kanapka-theme-main' ),
			kanapka_theme_asset_version( '/assets/css/pages/front-page.css' )
		);
	}

	if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		wp_enqueue_style(
			'kanapka-theme-woocommerce',
			get_theme_file_uri( '/assets/css/woocommerce/base.css' ),
			array( 'kanapka-theme-main' ),
			kanapka_theme_asset_version( '/assets/css/woocommerce/base.css' )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'kanapka_theme_enqueue_assets' );
