/**
 * Mobile order benefits carousel hint and control.
 */
( function () {
	'use strict';

	document.querySelectorAll( '[data-order-benefits]' ).forEach( ( section ) => {
		const track = section.querySelector( '[data-order-benefits-track]' );
		const next = section.querySelector( '[data-order-benefits-next]' );

		if ( ! track || ! next ) {
			return;
		}

		next.addEventListener( 'click', () => {
			const maxScroll = track.scrollWidth - track.clientWidth;
			const isAtEnd = track.scrollLeft >= maxScroll - 8;
			const items = Array.from( track.children );
			const nextItem = items.find( ( item ) => item.offsetLeft > track.scrollLeft + 8 );

			track.scrollTo( {
				left: isAtEnd ? 0 : Math.min( nextItem ? nextItem.offsetLeft : maxScroll, maxScroll ),
				behavior: 'smooth',
			} );
		} );
	} );
}() );
