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

	placePayment();
	$( document.body ).on( 'updated_checkout', placePayment );
}( jQuery ) );
