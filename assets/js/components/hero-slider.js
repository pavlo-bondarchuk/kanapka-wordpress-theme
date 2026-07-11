/**
 * Accessible, dependency-free homepage hero slider.
 */
( function () {
	'use strict';

	document.querySelectorAll( '[data-hero-slider]' ).forEach( ( slider ) => {
		const slides = Array.from( slider.querySelectorAll( '[data-hero-slide]' ) );
		const dots = Array.from( slider.querySelectorAll( '[data-hero-dot]' ) );
		const previous = slider.querySelector( '[data-hero-previous]' );
		const next = slider.querySelector( '[data-hero-next]' );
		const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );
		const delay = Math.max( 3000, Number.parseInt( slider.dataset.autoplayDelay, 10 ) || 6000 );
		let activeIndex = 0;
		let autoplayTimer;

		if ( slides.length < 2 ) {
			return;
		}

		const showSlide = ( requestedIndex ) => {
			activeIndex = ( requestedIndex + slides.length ) % slides.length;

			slides.forEach( ( slide, index ) => {
				const isActive = index === activeIndex;

				slide.classList.toggle( 'is-active', isActive );
				slide.setAttribute( 'aria-hidden', isActive ? 'false' : 'true' );
				slide.inert = ! isActive;
			} );

			dots.forEach( ( dot, index ) => {
				dot.setAttribute( 'aria-current', index === activeIndex ? 'true' : 'false' );
			} );
		};

		const stopAutoplay = () => {
			window.clearInterval( autoplayTimer );
			autoplayTimer = undefined;
		};

		const startAutoplay = () => {
			stopAutoplay();

			if (
				reducedMotion.matches ||
				document.hidden ||
				slider.matches( ':hover' ) ||
				slider.contains( document.activeElement )
			) {
				return;
			}

			autoplayTimer = window.setInterval( () => {
				showSlide( activeIndex + 1 );
			}, delay );
		};

		previous?.addEventListener( 'click', () => {
			showSlide( activeIndex - 1 );
			startAutoplay();
		} );

		next?.addEventListener( 'click', () => {
			showSlide( activeIndex + 1 );
			startAutoplay();
		} );

		dots.forEach( ( dot ) => {
			dot.addEventListener( 'click', () => {
				showSlide( Number.parseInt( dot.dataset.heroDot, 10 ) );
				startAutoplay();
			} );
		} );

		slider.addEventListener( 'mouseenter', stopAutoplay );
		slider.addEventListener( 'mouseleave', startAutoplay );
		slider.addEventListener( 'focusin', stopAutoplay );
		slider.addEventListener( 'focusout', ( event ) => {
			if ( ! slider.contains( event.relatedTarget ) ) {
				startAutoplay();
			}
		} );
		slider.addEventListener( 'keydown', ( event ) => {
			if ( 'ArrowLeft' === event.key ) {
				showSlide( activeIndex - 1 );
			} else if ( 'ArrowRight' === event.key ) {
				showSlide( activeIndex + 1 );
			}
		} );

		document.addEventListener( 'visibilitychange', () => {
			if ( document.hidden ) {
				stopAutoplay();
			} else {
				startAutoplay();
			}
		} );
		if ( 'function' === typeof reducedMotion.addEventListener ) {
			reducedMotion.addEventListener( 'change', startAutoplay );
		} else {
			reducedMotion.addListener( startAutoplay );
		}

		showSlide( 0 );
		startAutoplay();
	} );
}() );
