<?php
/**
 * Shared UI icons.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render a theme-owned SVG icon.
 *
 * @param string $name Icon key.
 * @param int    $size Icon width and height in pixels.
 * @return string
 */
function kanapka_theme_ui_icon( $name, $size = 24 ) {
	$size  = absint( $size );
	$size  = $size ? $size : 24;
	$attrs = sprintf(
		'viewBox="0 0 24 24" width="%1$d" height="%1$d" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"',
		$size
	);

	$icons = array(
		'cart'          => '<path d="M3 4h2l2.2 10.1a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.4L21 8H7"/><path d="M10 19a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/><path d="M19 19a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>',
		'search'        => '<path d="m21 21-4.35-4.35"/><circle cx="11" cy="11" r="8"/>',
		'chevron-left'  => '<path d="m15 5-7 7 7 7"/>',
		'chevron-right' => '<path d="m9 5 7 7-7 7"/>',
		'delivery'      => '<path d="M3 6h11v10H3z"/><path d="M14 10h4l3 3v3h-7z"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/>',
		'clock'         => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'leaf'          => '<path d="M20 4C11 4 5 8 5 15c0 3 2 5 5 5 7 0 10-7 10-16Z"/><path d="M4 21c3-6 7-9 12-12"/>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg %1$s stroke-width="%2$s">%3$s</svg>',
		$attrs,
		esc_attr( in_array( $name, array( 'delivery', 'clock', 'leaf' ), true ) ? '1.7' : '2' ),
		$icons[ $name ]
	);
}
