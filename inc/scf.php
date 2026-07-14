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
			'page_title' => __( 'Kanapka theme settings', 'kanapka-theme' ),
			'menu_title' => __( 'Theme settings', 'kanapka-theme' ),
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
 * Read a multiline option as plain text regardless of SCF formatting settings.
 *
 * @param string $name    Field name.
 * @param string $default Fallback value.
 * @return string
 */
function kanapka_theme_get_multiline_option( $name, $default = '' ) {
	$value = (string) kanapka_theme_get_option( $name, $default );
	$value = preg_replace( '/<br\s*\/?\s*>/i', "\n", $value );

	return sanitize_textarea_field( $value );
}

/**
 * Seed SCF options from the previous OXY configuration once.
 */
function kanapka_theme_migrate_legacy_options() {
	if ( ! function_exists( 'update_field' ) ) {
		return;
	}

	$migration_version = (int) get_option( 'kanapka_theme_scf_migration_version', get_option( 'kanapka_theme_scf_migrated' ) ? 1 : 0 );
	$legacy_mods     = get_option( 'theme_mods_oxy1', array() );
	$legacy_settings = maybe_unserialize( $legacy_mods['oxy-general-settings'] ?? array() );
	$legacy_mega     = $legacy_settings['oxy_mega_menu'][1] ?? array();

	if ( $migration_version < 1 ) {
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
	}

	if ( $migration_version < 2 ) {
		update_field( 'field_kanapka_order_label', __( 'Order reception', 'kanapka-theme' ), 'option' );
		update_field( 'field_kanapka_header_work_hours', get_option( 'oxy_contact_hours1', "Online orders accepted 24/7\nManager available from 9:00 to 18:00" ), 'option' );
		update_field( 'field_kanapka_contact_menu_enabled', ! empty( $legacy_settings['oxy_menu_contacts_block_status'] ) ? 1 : 0, 'option' );
		update_field( 'field_kanapka_contact_menu_parent_page', absint( $legacy_settings['oxy_menu_contacts_page'] ?? 0 ), 'option' );
		update_field( 'field_kanapka_contact_column_title', $legacy_settings['oxy_contacts_title1'] ?? __( 'Contacts', 'kanapka-theme' ), 'option' );
		update_field( 'field_kanapka_contact_address_one', $legacy_settings['oxy_contact_location1'] ?? __( 'Kyiv', 'kanapka-theme' ), 'option' );
		update_field( 'field_kanapka_contact_address_two', $legacy_settings['oxy_contact_location2'] ?? '', 'option' );
		update_field( 'field_kanapka_contact_hours_title', __( 'Orders are accepted', 'kanapka-theme' ), 'option' );
		update_field( 'field_kanapka_contact_button_label', __( 'Contact form', 'kanapka-theme' ), 'option' );
		update_field( 'field_kanapka_contact_button_url', get_permalink( absint( $legacy_settings['oxy_menu_contacts_page'] ?? 0 ) ), 'option' );
	}

	if ( $migration_version < 3 ) {
		update_field( 'field_kanapka_gtm_enabled', 0, 'option' );
		update_field( 'field_kanapka_ga4_enabled', 0, 'option' );
		update_field( 'field_kanapka_ua_enabled', 1, 'option' );
		update_field( 'field_kanapka_ua_id', 'UA-58514353-1', 'option' );
		update_field( 'field_kanapka_google_ads_enabled', 1, 'option' );
		update_field( 'field_kanapka_google_ads_id', '967972312', 'option' );
		update_field( 'field_kanapka_meta_pixel_enabled', 1, 'option' );
		update_field( 'field_kanapka_meta_pixel_id', '1730975970565201', 'option' );
		update_field( 'field_kanapka_yandex_metrika_enabled', 1, 'option' );
		update_field( 'field_kanapka_yandex_metrika_id', '27891162', 'option' );
	}

	if ( $migration_version < 4 ) {
		$catering_paths = array(
			'kanapka-kejtering',
			'kanapka-kejtering/vyezdnoj-furshet',
			'kanapka-kejtering/vyezdnoj-banket',
			'kanapka-kejtering/kofe-brejk-kiev',
			'kanapka-kejtering/barbekyu-kejterin',
		);

		foreach ( $catering_paths as $catering_path ) {
			$catering_page = get_page_by_path( $catering_path );

			if ( $catering_page instanceof WP_Post ) {
				update_post_meta( $catering_page->ID, '_wp_page_template', 'page-templates/catering-service.php' );
			}
		}
	}

	if ( $migration_version < 5 ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$catering_pages = get_pages(
			array(
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'page-templates/catering-service.php',
			)
		);

		foreach ( $catering_pages as $catering_page ) {
			$gallery_ids = (array) get_field( 'kanapka_catering_gallery_images', $catering_page->ID );

			foreach ( array_filter( array_map( 'absint', $gallery_ids ) ) as $gallery_id ) {
				wp_update_image_subsizes( $gallery_id );
			}
		}
	}

	update_option( 'kanapka_theme_scf_migrated', 1, false );
	update_option( 'kanapka_theme_scf_migration_version', 5, false );
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
