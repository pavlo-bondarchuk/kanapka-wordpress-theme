/**
 * Expandable homepage SEO text.
 */
( function () {
	'use strict';

	document.querySelectorAll( '[data-home-seo]' ).forEach( ( section ) => {
		const button = section.querySelector( '[data-home-seo-toggle]' );
		const label = section.querySelector( '[data-home-seo-toggle-label]' );
		const text = section.querySelector( '[data-home-seo-text]' );

		if ( ! button || ! label || ! text ) {
			return;
		}

		button.addEventListener( 'click', () => {
			const isExpanded = 'true' === button.getAttribute( 'aria-expanded' );

			button.setAttribute( 'aria-expanded', isExpanded ? 'false' : 'true' );
			section.classList.toggle( 'is-expanded', ! isExpanded );
			label.textContent = isExpanded ? 'Показати більше' : 'Згорнути';
		} );
	} );
}() );
