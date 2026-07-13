( () => {
	'use strict';

	const product = document.querySelector( '[data-single-product]' );
	if ( ! product ) {
		return;
	}

	const mainImage = product.querySelector( '[data-product-gallery-main]' );
	product.addEventListener( 'click', ( event ) => {
		const thumbnail = event.target.closest( '[data-product-gallery-thumb]' );
		if ( thumbnail && mainImage ) {
			const source = thumbnail.dataset.fullSrc;
			if ( source ) {
				mainImage.src = source;
				mainImage.removeAttribute( 'srcset' );
				mainImage.removeAttribute( 'sizes' );
			}

			product.querySelectorAll( '[data-product-gallery-thumb]' ).forEach( ( button ) => {
				const active = button === thumbnail;
				button.classList.toggle( 'is-active', active );
				button.setAttribute( 'aria-pressed', String( active ) );
			} );
			return;
		}

		const quantityControl = event.target.closest( '[data-product-quantity]' );
		if ( quantityControl ) {
			const input = quantityControl.querySelector( '[data-product-quantity-input]' );
			const addToCart = product.querySelector( '[data-kanapka-add-to-cart]' );
			if ( ! input ) {
				return;
			}

			let value = Math.max( 1, Number.parseInt( input.value, 10 ) || 1 );
			if ( event.target.closest( '[data-product-quantity-minus]' ) ) {
				value = Math.max( 1, value - 1 );
			} else if ( event.target.closest( '[data-product-quantity-plus]' ) ) {
				value += 1;
			} else {
				return;
			}

			input.value = value;
			if ( addToCart ) {
				addToCart.dataset.quantity = String( value );
			}
			return;
		}

		const share = event.target.closest( '[data-product-share]' );
		if ( share ) {
			const data = { title: share.dataset.shareTitle, url: share.dataset.shareUrl };
			if ( navigator.share ) {
				navigator.share( data ).catch( () => {} );
			} else if ( navigator.clipboard ) {
				navigator.clipboard.writeText( data.url ).catch( () => {} );
			}
		}
	} );

	product.addEventListener( 'input', ( event ) => {
		if ( ! event.target.matches( '[data-product-quantity-input]' ) ) {
			return;
		}

		const value = Math.max( 1, Number.parseInt( event.target.value, 10 ) || 1 );
		const addToCart = product.querySelector( '[data-kanapka-add-to-cart]' );
		if ( addToCart ) {
			addToCart.dataset.quantity = String( value );
		}
	} );
} )();
