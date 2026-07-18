<?php
/**
 * Checkout delivery date and time fields.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return future delivery dates and their available 15-minute time slots.
 *
 * The legacy option remains the source of truth so existing settings survive
 * removal of the old delivery-date plugin.
 *
 * @return array<string, array<int, string>>
 */
function kanapka_theme_delivery_schedule() {
	$rows     = get_option( 'wcd_date_time', array() );
	$settings = get_option( 'wcd_settings', array() );
	$format   = ! empty( $settings['date_format'] ) ? sanitize_text_field( $settings['date_format'] ) : 'Y-m-d';
	$timezone = wp_timezone();
	$today    = current_datetime()->setTime( 0, 0 );
	$schedule = array();

	if ( ! is_array( $rows ) ) {
		return $schedule;
	}

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) || empty( $row['date'] ) ) {
			continue;
		}

		$date = DateTimeImmutable::createFromFormat( '!d-m-Y', trim( (string) $row['date'] ), $timezone );

		if ( ! $date || $date < $today ) {
			continue;
		}

		$open  = kanapka_theme_normalize_delivery_time( $row['s_time'] ?? '09:00', '09:00' );
		$close = kanapka_theme_normalize_delivery_time( $row['c_time'] ?? '20:00', '20:00' );
		$start = DateTimeImmutable::createFromFormat( '!H:i', $open, $timezone );
		$end   = DateTimeImmutable::createFromFormat( '!H:i', $close, $timezone );

		if ( ! $start || ! $end || $end < $start ) {
			continue;
		}

		$slots = array();
		for ( $slot = $start; $slot <= $end; $slot = $slot->modify( '+15 minutes' ) ) {
			$slots[] = $slot->format( 'H:i' );
		}

		$schedule[ $date->format( $format ) ] = $slots;
	}

	return apply_filters( 'kanapka_theme_delivery_schedule', $schedule );
}

/**
 * Normalize an admin-entered delivery time.
 *
 * @param mixed  $value    Saved time.
 * @param string $fallback Default time.
 * @return string
 */
function kanapka_theme_normalize_delivery_time( $value, $fallback ) {
	$value = trim( (string) $value );

	if ( preg_match( '/^(\d{1,2})(?::(\d{1,2}))?$/', $value, $matches ) ) {
		$hour   = (int) $matches[1];
		$minute = isset( $matches[2] ) ? (int) $matches[2] : 0;

		if ( $hour < 24 && $minute < 60 ) {
			return sprintf( '%02d:%02d', $hour, $minute );
		}
	}

	return $fallback;
}

/** Add the delivery controls to the standard checkout details section. */
function kanapka_theme_checkout_delivery_fields() {
	$schedule = kanapka_theme_delivery_schedule();
	$options  = array( '' => __( 'Select an option&hellip;', 'woocommerce' ) );

	foreach ( array_keys( $schedule ) as $date ) {
		$options[ $date ] = $date;
	}

	printf(
		'<div class="checkout-delivery-fields" data-delivery-schedule="%s">',
		esc_attr( wp_json_encode( $schedule ) )
	);

	woocommerce_form_field(
		'wcd_dd',
		array(
			'type'     => 'select',
			'class'    => array( 'form-row-wide' ),
			'required' => true,
			'label'    => __( 'Delivery date', 'kanapka-theme' ),
			'options'  => $options,
		),
		WC()->checkout()->get_value( 'wcd_dd' )
	);

	woocommerce_form_field(
		'wcd_dt',
		array(
			'type'     => 'select',
			'class'    => array( 'form-row-wide' ),
			'required' => true,
			'label'    => __( 'Delivery time', 'kanapka-theme' ),
			'options'  => array( '' => __( 'Select an option&hellip;', 'woocommerce' ) ),
		),
		WC()->checkout()->get_value( 'wcd_dt' )
	);

	echo '</div>';
}
add_action( 'woocommerce_after_order_notes', 'kanapka_theme_checkout_delivery_fields' );

/** Validate delivery fields against the current schedule. */
function kanapka_theme_validate_delivery_fields() {
	$date     = isset( $_POST['wcd_dd'] ) ? wc_clean( wp_unslash( $_POST['wcd_dd'] ) ) : '';
	$time     = isset( $_POST['wcd_dt'] ) ? wc_clean( wp_unslash( $_POST['wcd_dt'] ) ) : '';
	$schedule = kanapka_theme_delivery_schedule();

	if ( ! $date || ! isset( $schedule[ $date ] ) ) {
		wc_add_notice( __( 'Please select a valid delivery date.', 'kanapka-theme' ), 'error' );
	}

	if ( ! $time || ! $date || ! isset( $schedule[ $date ] ) || ! in_array( $time, $schedule[ $date ], true ) ) {
		wc_add_notice( __( 'Please select a valid delivery time.', 'kanapka-theme' ), 'error' );
	}
}
add_action( 'woocommerce_checkout_process', 'kanapka_theme_validate_delivery_fields' );

/**
 * Store delivery values through the WooCommerce order API (including HPOS).
 *
 * @param WC_Order $order Order being created.
 */
function kanapka_theme_store_delivery_fields( $order ) {
	$date = isset( $_POST['wcd_dd'] ) ? wc_clean( wp_unslash( $_POST['wcd_dd'] ) ) : '';
	$time = isset( $_POST['wcd_dt'] ) ? wc_clean( wp_unslash( $_POST['wcd_dt'] ) ) : '';

	if ( $date ) {
		$order->update_meta_data( 'Delivery-Date', $date );
	}

	if ( $time ) {
		$order->update_meta_data( 'Delivery-Time', $time );
	}
}
add_action( 'woocommerce_checkout_create_order', 'kanapka_theme_store_delivery_fields' );

/**
 * Show delivery details in emails and customer order details.
 *
 * @param array    $fields Existing order fields.
 * @param bool     $sent_to_admin Whether the email is for an administrator.
 * @param WC_Order $order Order object.
 * @return array
 */
function kanapka_theme_delivery_order_fields( $fields, $sent_to_admin, $order ) {
	$date = $order->get_meta( 'Delivery-Date' );
	$time = $order->get_meta( 'Delivery-Time' );

	if ( $date ) {
		$fields['kanapka_delivery_date'] = array(
			'label' => __( 'Delivery date', 'kanapka-theme' ),
			'value' => $date,
		);
	}

	if ( $time ) {
		$fields['kanapka_delivery_time'] = array(
			'label' => __( 'Delivery time', 'kanapka-theme' ),
			'value' => $time,
		);
	}

	return $fields;
}
add_filter( 'woocommerce_email_order_meta_fields', 'kanapka_theme_delivery_order_fields', 10, 3 );

/**
 * Show delivery details below the order table in My Account.
 *
 * @param WC_Order $order Order object.
 */
function kanapka_theme_customer_delivery_details( $order ) {
	$date = $order->get_meta( 'Delivery-Date' );
	$time = $order->get_meta( 'Delivery-Time' );

	if ( ! $date && ! $time ) {
		return;
	}

	echo '<p class="woocommerce-order-delivery"><strong>' . esc_html__( 'Delivery', 'kanapka-theme' ) . ':</strong> ';
	echo esc_html( trim( $date . ' ' . $time ) );
	echo '</p>';
}
add_action( 'woocommerce_order_details_after_order_table', 'kanapka_theme_customer_delivery_details' );

/**
 * Show delivery details in the order-data panel.
 *
 * @param WC_Order $order Order object.
 */
function kanapka_theme_admin_delivery_fields( $order ) {
	$date = $order->get_meta( 'Delivery-Date' );
	$time = $order->get_meta( 'Delivery-Time' );

	if ( ! $date && ! $time ) {
		return;
	}

	echo '<p class="form-field form-field-wide"><strong>' . esc_html__( 'Delivery', 'kanapka-theme' ) . ':</strong><br>';
	echo esc_html( trim( $date . ' ' . $time ) );
	echo '</p>';
}
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'kanapka_theme_admin_delivery_fields' );

/** Keep delivery details available to the existing print-document plugin. */
function kanapka_theme_print_delivery_fields( $fields, $order ) {
	return kanapka_theme_delivery_order_fields( $fields, true, $order );
}
add_filter( 'wcdn_order_info_fields', 'kanapka_theme_print_delivery_fields', 10, 2 );
