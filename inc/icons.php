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
		'users'         => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
		'briefcase'     => '<path d="M10 6V5a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v1"/><rect width="20" height="14" x="2" y="6" rx="2"/><path d="M2 12h20"/><path d="M12 11v2"/>',
		'sparkles'      => '<path d="m12 3-1.9 5.8L4 11l6.1 2.2L12 19l1.9-5.8L20 11l-6.1-2.2Z"/><path d="M5 3v4"/><path d="M3 5h4"/><path d="M19 17v4"/><path d="M17 19h4"/>',
		'utensils'      => '<path d="M4 3v8"/><path d="M8 3v8"/><path d="M4 7h4"/><path d="M6 11v10"/><path d="M16 3c2 2 3 4.3 3 7.2V21"/><path d="M16 3v18"/>',
		'percent'       => '<path d="m19 5-14 14"/><circle cx="7" cy="7" r="2"/><circle cx="17" cy="17" r="2"/>',
		'star'          => '<path d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.8 1-6.1-4.4-4.3 6.1-.9Z"/>',
		'view-grid'     => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>',
		'view-list'     => '<rect x="3" y="4" width="6" height="6"/><path d="M13 7h8"/><rect x="3" y="14" width="6" height="6"/><path d="M13 17h8"/>',
		'loader-circle' => '<path d="M21 12a9 9 0 1 1-6.219-8.56"/>',
		'circle-check'  => '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>',
		'minus'         => '<path d="M5 12h14"/>',
		'plus'          => '<path d="M12 5v14"/><path d="M5 12h14"/>',
		'share'         => '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 10.5 6.8-4"/><path d="m8.6 13.5 6.8 4"/>',
		'info'          => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>',
		'trash'         => '<path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>',
		'refresh'       => '<path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/>',
		'shield-check'  => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/>',
		'badge-check'   => '<path d="M12 3 14 5l3-.5.5 3 2 2-2 2 .5 3-3 .5-2 2-2-2-3 .5-.5-3-2-2 2-2-.5-3 3-.5Z"/><path d="m9 12 2 2 4-4"/>',
		'salad'         => '<path d="M7 21h10"/><path d="M12 21a9 9 0 0 0 9-9H3a9 9 0 0 0 9 9Z"/><path d="M11.38 12a2.4 2.4 0 0 1-.4-4.77 2.4 2.4 0 0 1 3.2-2.77 2.4 2.4 0 0 1 3.47-.63 2.4 2.4 0 0 1 3.37 3.37 2.4 2.4 0 0 1-1.1 3.7 2.51 2.51 0 0 1 .03 1.1"/><path d="m13 12 4-4"/><path d="M10.9 7.25A3.99 3.99 0 0 0 4 10c0 .73.2 1.41.54 2"/>',
		'hand-coins'    => '<path d="M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17"/><path d="m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"/><path d="m2 16 6 6"/><circle cx="16" cy="9" r="2.9"/><circle cx="6" cy="5" r="3"/>',
		'clock-arrow-up'=> '<path d="M12 6v6l1.56.78"/><path d="M13.227 21.925a10 10 0 1 1 8.767-9.588"/><path d="m14 18 4-4 4 4"/><path d="M18 22v-8"/>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg %1$s stroke-width="%2$s">%3$s</svg>',
		$attrs,
		esc_attr( in_array( $name, array( 'delivery', 'clock', 'leaf', 'users', 'briefcase', 'sparkles', 'utensils', 'percent', 'star' ), true ) ? '1.7' : '2' ),
		$icons[ $name ]
	);
}
