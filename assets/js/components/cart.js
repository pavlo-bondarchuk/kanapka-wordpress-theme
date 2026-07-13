/**
 * Update the WooCommerce cart after a quantity changes.
 */
( function () {
	'use strict';

	const root = document.documentElement;
	let updateTimer;
	let requestController;

	const getCartForm = ( element ) => element.closest( '.woocommerce-cart-form' );
	const replaceFragments = ( fragments ) => {
		Object.entries( fragments || {} ).forEach( ( [ selector, markup ] ) => {
			document.querySelectorAll( selector ).forEach( ( element ) => {
				element.outerHTML = markup;
			} );
		} );
	};
	const refreshMiniCart = async () => {
		const endpoint = window.kanapkaCartActions?.wcAjaxUrl?.replace( '%%endpoint%%', 'get_refreshed_fragments' );

		if ( ! endpoint ) {
			return;
		}

		try {
			const response = await fetch( endpoint, {
				method: 'POST',
				credentials: 'same-origin',
			} );
			const result = await response.json();

			if ( response.ok && result.fragments ) {
				replaceFragments( result.fragments );
				window.jQuery?.( document.body ).trigger( 'wc_fragments_refreshed' );
			}
		} catch ( error ) {
			// The cart body is already current; a later WooCommerce fragment refresh can retry the header.
		}
	};

	const refreshCart = async ( form, changedInput ) => {
		if ( ! form || ! changedInput.checkValidity() ) {
			return;
		}

		requestController?.abort();
		requestController = new AbortController();

		const inputName = changedInput.name;
		const formData = new FormData( form );
		const updateButton = form.querySelector( '[name="update_cart"]' );

		formData.set( 'update_cart', updateButton?.value || 'Update cart' );
		form.classList.add( 'is-updating' );
		form.setAttribute( 'aria-busy', 'true' );

		try {
			const response = await fetch( form.action, {
				method: 'POST',
				body: formData,
				credentials: 'same-origin',
				signal: requestController.signal,
			} );

			if ( ! response.ok ) {
				throw new Error( `Cart update failed with status ${ response.status }.` );
			}

			const html = await response.text();
			const nextDocument = new DOMParser().parseFromString( html, 'text/html' );
			const currentCart = document.querySelector( '.cart-page__content > .woocommerce' );
			const nextCart = nextDocument.querySelector( '.cart-page__content > .woocommerce' );

			if ( ! currentCart || ! nextCart ) {
				throw new Error( 'The updated cart markup was not found.' );
			}

			currentCart.replaceWith( nextCart );

			if ( inputName ) {
				Array.from( document.querySelectorAll( '.woocommerce-cart-form input.qty' ) )
					.find( ( input ) => input.name === inputName )
					?.focus( { preventScroll: true } );
			}

			if ( window.jQuery ) {
				window.jQuery( document.body ).trigger( 'updated_wc_div' );
			}

			await refreshMiniCart();

			document.body.dispatchEvent( new CustomEvent( 'kanapka_cart_updated' ) );
		} catch ( error ) {
			if ( 'AbortError' === error.name ) {
				return;
			}

			root.classList.remove( 'cart-ajax-enabled' );
			form.classList.remove( 'is-updating' );
			form.removeAttribute( 'aria-busy' );
		}
	};

	const scheduleUpdate = ( input, delay ) => {
		window.clearTimeout( updateTimer );
		updateTimer = window.setTimeout( () => refreshCart( getCartForm( input ), input ), delay );
	};

	document.addEventListener( 'input', ( event ) => {
		if ( event.target.matches( '.woocommerce-cart-form input.qty' ) ) {
			scheduleUpdate( event.target, 450 );
		}
	} );

	document.addEventListener( 'change', ( event ) => {
		if ( event.target.matches( '.woocommerce-cart-form input.qty' ) ) {
			scheduleUpdate( event.target, 150 );
		}
	} );

	if ( document.querySelector( '.woocommerce-cart-form' ) ) {
		root.classList.add( 'cart-ajax-enabled' );
	}
}() );
