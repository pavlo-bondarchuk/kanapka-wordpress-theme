/**
 * Catalogue sidebar controls.
 */
( function () {
	'use strict';

	document.querySelectorAll( '.catalogue-sidebar' ).forEach( ( sidebar ) => {
		const toggle = sidebar.querySelector( '[data-catalogue-sidebar-toggle]' );
		const content = sidebar.querySelector( '[data-catalogue-sidebar-content]' );
		const label = sidebar.querySelector( '[data-catalogue-sidebar-toggle-label]' );

		if ( ! toggle || ! content ) {
			return;
		}

		sidebar.classList.add( 'is-collapsible' );

		const setExpanded = ( isExpanded ) => {
			toggle.setAttribute( 'aria-expanded', String( isExpanded ) );
			toggle.setAttribute( 'aria-label', isExpanded ? toggle.dataset.closeLabel : toggle.dataset.openLabel );
			if ( label ) {
				label.textContent = isExpanded ? toggle.dataset.closeLabel : toggle.dataset.openLabel;
			}
		};

		if ( label ) {
			const header = document.querySelector( '[data-site-header]' );
			const adminBar = document.getElementById( 'wpadminbar' );
			const setStickyOffset = () => {
				const headerHeight = header ? header.getBoundingClientRect().height : 0;
				const adminBarHeight = adminBar && window.getComputedStyle( adminBar ).position === 'fixed' ? adminBar.getBoundingClientRect().height : 0;

				sidebar.style.setProperty( '--catalogue-mobile-sticky-top', `${ headerHeight + adminBarHeight + 8 }px` );
			};

			setStickyOffset();
			window.addEventListener( 'resize', setStickyOffset, { passive: true } );
			if ( 'ResizeObserver' in window && header ) {
				new ResizeObserver( setStickyOffset ).observe( header );
			}
		}

		setExpanded( false );
		toggle.addEventListener( 'click', () => {
			setExpanded( toggle.getAttribute( 'aria-expanded' ) !== 'true' );
		} );
	} );

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

	document.querySelectorAll( '[data-price-range-filter]' ).forEach( ( filter ) => {
		const range = filter.querySelector( '[data-price-range]' );
		const rangeMin = filter.querySelector( '[data-price-range-min]' );
		const rangeMax = filter.querySelector( '[data-price-range-max]' );
		const inputMin = filter.querySelector( '[data-price-input-min]' );
		const inputMax = filter.querySelector( '[data-price-input-max]' );

		if ( ! range || ! rangeMin || ! rangeMax || ! inputMin || ! inputMax ) {
			return;
		}

		const limit = Number.parseInt( rangeMax.max, 10 ) || 1;
		const clamp = ( value ) => Math.min( limit, Math.max( 0, Number.parseInt( value, 10 ) || 0 ) );
		const render = ( minValue, maxValue ) => {
			const min = Math.min( clamp( minValue ), clamp( maxValue ) );
			const max = Math.max( clamp( minValue ), clamp( maxValue ) );

			rangeMin.value = min;
			rangeMax.value = max;
			inputMin.value = min;
			inputMax.value = max;
			range.style.setProperty( '--range-start', `${ ( min / limit ) * 100 }%` );
			range.style.setProperty( '--range-end', `${ ( max / limit ) * 100 }%` );
		};

		rangeMin.addEventListener( 'input', () => render( Math.min( Number( rangeMin.value ), Number( rangeMax.value ) ), rangeMax.value ) );
		rangeMax.addEventListener( 'input', () => render( rangeMin.value, Math.max( Number( rangeMax.value ), Number( rangeMin.value ) ) ) );
		inputMin.addEventListener( 'input', () => render( Math.min( clamp( inputMin.value ), Number( inputMax.value ) ), inputMax.value ) );
		inputMax.addEventListener( 'input', () => render( inputMin.value, Math.max( clamp( inputMax.value ), Number( inputMin.value ) ) ) );

		render( inputMin.value, inputMax.value );
	} );
}() );
