<?php
/**
 * Optional Secure Custom Fields integration.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the theme settings page provided by Secure Custom Fields.
 */
function kanapka_theme_register_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Kanapka Theme Settings', 'kanapka-theme' ),
			'menu_title' => __( 'Theme Settings', 'kanapka-theme' ),
			'menu_slug'  => 'kanapka-theme-settings',
			'capability' => 'manage_options',
			'redirect'   => false,
			'position'   => 61,
			'icon_url'   => 'dashicons-admin-customizer',
		)
	);
}
add_action( 'acf/init', 'kanapka_theme_register_options_page' );

/**
 * Read a theme option without making SCF a hard dependency.
 *
 * @param string $name    Field name.
 * @param mixed  $default Fallback value.
 * @return mixed
 */
function kanapka_theme_get_option( $name, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}

	$value = get_field( $name, 'option' );

	return null === $value || false === $value || '' === $value ? $default : $value;
}

/**
 * Seed SCF options from the previous OXY configuration once.
 */
function kanapka_theme_migrate_legacy_options() {
	if ( ! function_exists( 'update_field' ) || get_option( 'kanapka_theme_scf_migrated' ) ) {
		return;
	}

	$legacy_mods     = get_option( 'theme_mods_oxy1', array() );
	$legacy_settings = maybe_unserialize( $legacy_mods['oxy-general-settings'] ?? array() );
	$legacy_mega     = $legacy_settings['oxy_mega_menu'][1] ?? array();

	update_field( 'field_kanapka_mega_enabled', ! empty( $legacy_mega ) ? 1 : 0, 'option' );
	update_field( 'field_kanapka_mega_parent_page', absint( $legacy_mega['menu_id'] ?? 0 ), 'option' );
	update_field( 'field_kanapka_mega_base_category', absint( $legacy_mega['category_id'] ?? 0 ), 'option' );
	update_field( 'field_kanapka_mega_show_images', isset( $legacy_mega['show_icon'] ) && '2' === (string) $legacy_mega['show_icon'] ? 1 : 0, 'option' );
	update_field( 'field_kanapka_mega_child_limit', absint( $legacy_mega['limit'] ?? 0 ), 'option' );
	update_field( 'field_kanapka_mega_orderby', sanitize_key( $legacy_mega['orderby'] ?? 'menu_order' ), 'option' );
	update_field( 'field_kanapka_mega_order', 'DESC' === ( $legacy_mega['order'] ?? '' ) ? 'DESC' : 'ASC', 'option' );
	update_field( 'field_kanapka_header_phone_one', $legacy_settings['oxy_contact_mphone2'] ?? '(066) 691-72-72', 'option' );
	update_field( 'field_kanapka_header_phone_two', preg_replace( '/\s*\([^)]*(?:Viber|WhatsApp|Telegram)[^)]*\)\s*/i', '', $legacy_settings['oxy_contact_mphone1'] ?? '(093) 691-72-72' ), 'option' );
	update_field( 'field_kanapka_contact_email', $legacy_settings['oxy_contact_email1'] ?? get_option( 'admin_email' ), 'option' );

	update_option( 'kanapka_theme_scf_migrated', 1, false );
}
add_action( 'acf/init', 'kanapka_theme_migrate_legacy_options', 20 );

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
