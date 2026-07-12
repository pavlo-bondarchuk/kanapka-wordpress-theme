/**
 * Catalogue sidebar controls.
 */
( function () {
	'use strict';

	document.querySelectorAll( '[data-catalogue-category-toggle]' ).forEach( ( toggle ) => {
		const panel = toggle.closest( '.catalogue-panel' );
		const label = toggle.querySelector( '[data-catalogue-category-toggle-label]' );
		const extraCategories = panel ? panel.querySelectorAll( '[data-catalogue-category-extra]' ) : [];
		const showLabel = toggle.dataset.showLabel || '';
		const hideLabel = toggle.dataset.hideLabel || '';

		if ( ! panel || ! label || ! extraCategories.length ) {
			return;
		}

		toggle.addEventListener( 'click', () => {
			const isExpanded = toggle.getAttribute( 'aria-expanded' ) === 'true';
			const nextExpanded = ! isExpanded;

			extraCategories.forEach( ( category ) => {
				category.hidden = ! nextExpanded;
			} );

			toggle.setAttribute( 'aria-expanded', nextExpanded ? 'true' : 'false' );
			label.textContent = nextExpanded ? hideLabel : showLabel;
		} );
	} );
}() );
