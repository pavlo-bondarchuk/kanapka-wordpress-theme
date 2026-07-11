/**
 * Lightweight homepage logo slider.
 */
( function () {
	'use strict';

	const edgeTolerance = 4;

	document.querySelectorAll( '[data-logo-slider]' ).forEach( ( slider ) => {
		const track = slider.querySelector( '[data-logo-slider-track]' );
		const previous = slider.querySelector( '[data-logo-slider-previous]' );
		const next = slider.querySelector( '[data-logo-slider-next]' );

		if ( ! track || ! previous || ! next ) {
			return;
		}

		const updateControls = () => {
			const maxScroll = track.scrollWidth - track.clientWidth;
			const canScroll = maxScroll > edgeTolerance;

			previous.disabled = ! canScroll || track.scrollLeft <= edgeTolerance;
			next.disabled = ! canScroll || track.scrollLeft >= maxScroll - edgeTolerance;
		};

		const scrollByPage = ( direction ) => {
			track.scrollBy( {
				left: direction * track.clientWidth,
				behavior: 'smooth',
			} );
		};

		previous.addEventListener( 'click', () => scrollByPage( -1 ) );
		next.addEventListener( 'click', () => scrollByPage( 1 ) );
		track.addEventListener( 'scroll', updateControls, { passive: true } );
		window.addEventListener( 'resize', updateControls );

		updateControls();
	} );
}() );
