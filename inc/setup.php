<?php
/**
 * Theme setup.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Match the frontend locale to Weglot's original language.
 *
 * WordPress is configured in Ukrainian for the dashboard, while Weglot treats
 * Russian as the original site language. Rendering frontend gettext strings
 * in Russian gives Weglot the source language it expects and prevents
 * Ukrainian translations from leaking into the Russian version.
 *
 * @param string|null $locale Previously determined locale, if any.
 * @return string|null
 */
function kanapka_theme_frontend_source_locale( $locale ) {
	if ( ( is_admin() && ! wp_doing_ajax() ) || wp_doing_cron() || ! function_exists( 'weglot_get_original_language' ) ) {
		return $locale;
	}

	try {
		$original_language = strtolower( (string) weglot_get_original_language() );
	} catch ( Throwable $exception ) {
		return $locale;
	}

	$locale_map = array(
		'ru' => 'ru_RU',
		'uk' => 'uk',
		'en' => 'en_US',
	);

	return $locale_map[ $original_language ] ?? $locale;
}
add_filter( 'pre_determine_locale', 'kanapka_theme_frontend_source_locale', 20 );

/**
 * Register theme supports.
 */
function kanapka_theme_setup() {
	load_theme_textdomain( 'kanapka-theme', get_theme_file_path( '/languages' ) );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 420,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'kanapka_theme_setup' );
