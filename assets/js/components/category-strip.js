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

		track.addEventListener( 'pointerdown', () => {
			track.classList.add( 'has-scroll-snap' );
		}, { once: true, passive: true } );

		next.addEventListener( 'click', () => {
			const maxScroll = track.scrollWidth - track.clientWidth;
			const isAtEnd = track.scrollLeft >= maxScroll - 8;
			const items = Array.from( track.children );
			const nextPageEdge = track.scrollLeft + track.clientWidth - 8;
			const nextItem = items.find( ( item ) => item.offsetLeft >= nextPageEdge );

			track.scrollTo( {
				left: isAtEnd ? 0 : Math.min( nextItem ? nextItem.offsetLeft : maxScroll, maxScroll ),
				behavior: 'smooth',
			} );
		} );
	} );
}() );
