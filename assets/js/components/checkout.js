/**
 * Keep the standard WooCommerce payment block in the checkout details card.
 */
( function ( $ ) {
	'use strict';

	const placePayment = () => {
		const slot = document.querySelector( '[data-checkout-payment-slot]' );
		const payment = document.querySelector( '.woocommerce-checkout-payment' );

		if ( slot && payment && payment.parentElement !== slot ) {
			slot.appendChild( payment );
		}
	};

	const setupDeliveryFields = () => {
		const fields = document.querySelector( '[data-delivery-schedule]' );
		const dateField = fields?.querySelector( '#wcd_dd' );
		const timeField = fields?.querySelector( '#wcd_dt' );

		if ( ! fields || ! dateField || ! timeField ) {
			return;
		}

		let schedule = {};
		try {
			schedule = JSON.parse( fields.dataset.deliverySchedule );
		} catch ( error ) {
			return;
		}

		const updateTimes = () => {
			const selectedTime = timeField.value;
			const placeholder = timeField.options[ 0 ]?.textContent || '';
			const times = schedule[ dateField.value ] || [];

			timeField.replaceChildren( new Option( placeholder, '' ) );
			times.forEach( ( time ) => timeField.add( new Option( time, time, false, time === selectedTime ) ) );
		};

		if ( dateField.dataset.deliveryReady !== 'true' ) {
			dateField.addEventListener( 'change', updateTimes );
			dateField.dataset.deliveryReady = 'true';
		}

		updateTimes();
	};

	placePayment();
	setupDeliveryFields();
	$( document.body ).on( 'updated_checkout', () => {
		placePayment();
		setupDeliveryFields();
	} );
}( jQuery ) );
