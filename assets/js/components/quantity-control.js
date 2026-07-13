/**
 * Shared visible minus/plus controls for product quantity inputs.
 */
( function () {
	'use strict';

	document.addEventListener( 'click', ( event ) => {
		const button = event.target.closest( '[data-quantity-decrease], [data-quantity-increase]' );

		if ( ! button ) {
			return;
		}

		const control = button.closest( '[data-quantity-control], .quantity' );
		const input = control?.querySelector( 'input[type="number"]' );

		if ( ! input || input.disabled || input.readOnly ) {
			return;
		}

		event.preventDefault();

		const step = Number.parseFloat( input.step ) || 1;
		const min = '' === input.min ? Number.NEGATIVE_INFINITY : Number.parseFloat( input.min );
		const max = '' === input.max ? Number.POSITIVE_INFINITY : Number.parseFloat( input.max );
		const current = Number.parseFloat( input.value ) || ( Number.isFinite( min ) ? min : 0 );
		const direction = button.hasAttribute( 'data-quantity-increase' ) ? 1 : -1;
		const precision = ( String( step ).split( '.' )[ 1 ] || '' ).length;
		const next = Math.min( max, Math.max( min, current + ( direction * step ) ) );

		input.value = next.toFixed( precision );
		input.dispatchEvent( new Event( 'input', { bubbles: true } ) );
		input.dispatchEvent( new Event( 'change', { bubbles: true } ) );
		input.focus();
	} );
}() );
