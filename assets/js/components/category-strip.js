/**
 * Lightweight category strip controls.
 */
( function () {
	'use strict';

	document.querySelectorAll( '[data-category-strip]' ).forEach( ( strip ) => {
		const track = strip.querySelector( '[data-category-strip-track]' );
		const next = strip.querySelector( '[data-category-strip-next]' );

		if ( ! track || ! next ) {
			return;
		}

		next.addEventListener( 'click', () => {
			const maxScroll = track.scrollWidth - track.clientWidth;
			const isAtEnd = track.scrollLeft >= maxScroll - 8;

			track.scrollTo( {
				left: isAtEnd ? 0 : Math.min( track.scrollLeft + track.clientWidth, maxScroll ),
				behavior: 'smooth',
			} );
		} );
	} );
}() );
