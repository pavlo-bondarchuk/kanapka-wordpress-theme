/**
 * Keep desktop mega menus open while the pointer crosses the header gap.
 */
( function () {
	'use strict';

	const desktopQuery = window.matchMedia( '(min-width: 68.0625rem)' );
	const menuItems = document.querySelectorAll( '.menu-item-has-mega-menu' );

	menuItems.forEach( ( item ) => {
		const panel = item.querySelector( ':scope > .category-mega-menu' );

		if ( ! panel ) {
			return;
		}

		let closeTimer;

		const openMenu = () => {
			if ( ! desktopQuery.matches ) {
				return;
			}

			window.clearTimeout( closeTimer );
			item.classList.add( 'is-mega-menu-open' );
		};

		const scheduleClose = () => {
			if ( ! desktopQuery.matches ) {
				return;
			}

			window.clearTimeout( closeTimer );
			closeTimer = window.setTimeout( () => {
				item.classList.remove( 'is-mega-menu-open' );
			}, 220 );
		};

		item.addEventListener( 'pointerenter', openMenu );
		item.addEventListener( 'pointerleave', scheduleClose );
		panel.addEventListener( 'pointerenter', openMenu );
		panel.addEventListener( 'pointerleave', scheduleClose );
		item.addEventListener( 'focusin', openMenu );
		item.addEventListener( 'focusout', ( event ) => {
			if ( ! item.contains( event.relatedTarget ) ) {
				scheduleClose();
			}
		} );

		document.addEventListener( 'keydown', ( event ) => {
			if ( 'Escape' === event.key ) {
				window.clearTimeout( closeTimer );
				item.classList.remove( 'is-mega-menu-open' );
			}
		} );
	} );

	desktopQuery.addEventListener( 'change', ( event ) => {
		if ( event.matches ) {
			return;
		}

		menuItems.forEach( ( item ) => {
			item.classList.remove( 'is-mega-menu-open' );
		} );
	} );
}() );
