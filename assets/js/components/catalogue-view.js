/**
 * Catalogue grid/list view switcher.
 */
( function () {
	'use strict';

	const switcher = document.querySelector( '[data-catalogue-view-switcher]' );
	const products = document.querySelector( '[data-catalogue-products]' );

	if ( ! switcher || ! products ) {
		return;
	}

	const buttons = Array.from( switcher.querySelectorAll( '[data-catalogue-view]' ) );
	const storageKey = 'kanapkaCatalogueView';

	const setView = ( view ) => {
		const nextView = 'list' === view ? 'list' : 'grid';

		products.dataset.view = nextView;
		buttons.forEach( ( button ) => {
			button.setAttribute( 'aria-pressed', String( button.dataset.catalogueView === nextView ) );
		} );

		try {
			window.localStorage.setItem( storageKey, nextView );
		} catch ( error ) {
			// The control still works when browser storage is unavailable.
		}
	};

	let savedView = 'grid';

	try {
		savedView = window.localStorage.getItem( storageKey ) || savedView;
	} catch ( error ) {
		// Use the default grid view when browser storage is unavailable.
	}

	setView( savedView );
	buttons.forEach( ( button ) => {
		button.addEventListener( 'click', () => setView( button.dataset.catalogueView ) );
	} );
}() );
