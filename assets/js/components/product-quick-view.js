/**
 * Shared AJAX product quick view.
 */
( function () {
	'use strict';

	const config = window.kanapkaQuickView || {};
	const modal = document.querySelector( '[data-product-quick-view-modal]' );

	if ( ! modal || ! config.ajaxUrl || ! config.nonce ) {
		return;
	}

	const dialog = modal.querySelector( '[data-product-quick-view-dialog]' );
	const content = modal.querySelector( '[data-product-quick-view-content]' );
	const status = modal.querySelector( '[data-product-quick-view-status]' );
	let opener = null;
	let controller = null;

	const setCartButtonState = ( button, icon, label ) => {
		button.innerHTML = icon || '';
		const text = document.createElement( 'span' );
		text.textContent = label;
		button.append( text );
	};

	document.addEventListener( 'input', ( event ) => {
		const quantity = event.target.closest( '[data-product-card-quantity]' );

		if ( ! quantity ) {
			return;
		}

		const addToCart = quantity.closest( '.product-card' )?.querySelector( '[data-kanapka-add-to-cart]' );

		if ( addToCart ) {
			addToCart.dataset.quantity = Math.max( 1, Number.parseInt( quantity.value, 10 ) || 1 );
		}
	} );

	const closeModal = () => {
		if ( controller ) {
			controller.abort();
			controller = null;
		}

		modal.hidden = true;
		document.body.classList.remove( 'has-product-quick-view' );
		content.replaceChildren();
		status.hidden = false;

		if ( opener ) {
			opener.focus();
		}
	};

	const openModal = async ( button ) => {
		opener = button;
		modal.hidden = false;
		document.body.classList.add( 'has-product-quick-view' );
		content.replaceChildren();
		status.hidden = false;
		status.textContent = config.loadingLabel || 'Завантаження…';
		dialog.focus();

		controller = new AbortController();
		const body = new URLSearchParams( {
			action: 'kanapka_product_quick_view',
			nonce: config.nonce,
			product_id: button.dataset.productQuickView,
		} );

		try {
			const response = await window.fetch( config.ajaxUrl, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
				body,
				signal: controller.signal,
			} );
			const result = await response.json();

			if ( ! response.ok || ! result.success || ! result.data?.html ) {
				throw new Error( 'Invalid quick view response' );
			}

			content.innerHTML = result.data.html;
			status.hidden = true;

			const quantity = content.querySelector( '[data-quick-view-quantity]' );
			const addToCart = content.querySelector( '[data-quick-view-add-to-cart]' );

			if ( quantity && addToCart ) {
				quantity.addEventListener( 'input', () => {
					addToCart.dataset.quantity = Math.max( 1, Number.parseInt( quantity.value, 10 ) || 1 );
				} );
			}

			content.querySelector( 'a, button, input' )?.focus();
		} catch ( error ) {
			if ( 'AbortError' !== error.name ) {
				status.textContent = config.errorLabel || 'Не вдалося завантажити товар.';
			}
		} finally {
			controller = null;
		}
	};

	document.addEventListener( 'click', ( event ) => {
		const trigger = event.target.closest( '[data-product-quick-view]' );
		const addToCart = event.target.closest( '[data-kanapka-add-to-cart]' );

		if ( trigger ) {
			event.preventDefault();
			openModal( trigger );
			return;
		}

		if ( addToCart && addToCart.classList.contains( 'product_type_simple' ) && config.wcAjaxUrl ) {
			event.preventDefault();
			event.stopImmediatePropagation();

			if ( addToCart.classList.contains( 'is-loading' ) ) {
				return;
			}

			const productId = addToCart.dataset.product_id;
			const quantity = addToCart.dataset.quantity || '1';
			const endpoint = config.wcAjaxUrl.replace( '%%endpoint%%', 'add_to_cart' );
			const body = new URLSearchParams( { product_id: productId, quantity } );
			const originalMarkup = addToCart.innerHTML;

			addToCart.classList.add( 'is-loading' );
			addToCart.setAttribute( 'aria-disabled', 'true' );
			setCartButtonState( addToCart, config.loadingIcon, config.loadingLabel || 'Завантаження…' );

			window.fetch( endpoint, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
				body,
			} ).then( ( response ) => response.json() ).then( ( result ) => {
				if ( result.error ) {
					window.location.href = result.product_url || addToCart.href;
					return;
				}

				Object.entries( result.fragments || {} ).forEach( ( [ selector, html ] ) => {
					document.querySelectorAll( selector ).forEach( ( element ) => {
						element.outerHTML = html;
					} );
				} );

				addToCart.classList.add( 'is-added' );
				setCartButtonState( addToCart, config.successIcon, config.addedLabel || 'Додано в кошик' );
			} ).catch( () => {
				addToCart.innerHTML = originalMarkup;
			} ).finally( () => {
				addToCart.classList.remove( 'is-loading' );
				addToCart.removeAttribute( 'aria-disabled' );
			} );

			return;
		}

		if ( event.target.closest( '[data-product-quick-view-close]' ) ) {
			closeModal();
		}
	} );

	document.addEventListener( 'keydown', ( event ) => {
		if ( ! modal.hidden && 'Escape' === event.key ) {
			closeModal();
			return;
		}

		if ( ! modal.hidden && 'Tab' === event.key ) {
			const focusable = Array.from( dialog.querySelectorAll( 'a[href], button:not([disabled]), input:not([disabled])' ) );
			const first = focusable[ 0 ];
			const last = focusable[ focusable.length - 1 ];

			if ( event.shiftKey && document.activeElement === first ) {
				event.preventDefault();
				last.focus();
			} else if ( ! event.shiftKey && document.activeElement === last ) {
				event.preventDefault();
				first.focus();
			}
		}
	} );
}() );
