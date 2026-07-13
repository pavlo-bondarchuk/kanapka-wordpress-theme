( () => {
	'use strict';

	const product = document.querySelector( '[data-single-product]' );
	if ( ! product ) {
		return;
	}

	const mainImage = product.querySelector( '[data-product-gallery-main]' );
	const shareMenu = product.querySelector( '[data-product-share-menu]' );
	const shareToggle = shareMenu?.querySelector( '[data-product-share-toggle]' );
	const sharePopover = shareMenu?.querySelector( '[data-product-share-popover]' );

	const closeShareMenu = () => {
		if ( ! sharePopover || ! shareToggle ) {
			return;
		}
		sharePopover.hidden = true;
		shareToggle.setAttribute( 'aria-expanded', 'false' );
	};

	const openShareWindow = ( url ) => {
		window.open( url, 'kanapka-product-share', 'popup=yes,width=720,height=620,resizable=yes,scrollbars=yes' );
	};

	const getShareUrl = ( platform, data ) => {
		const url = encodeURIComponent( data.url );
		const title = encodeURIComponent( data.title );
		const text = encodeURIComponent( `${ data.title } ${ data.url }` );
		const image = encodeURIComponent( data.image || '' );

		const urls = {
			facebook: `https://www.facebook.com/sharer/sharer.php?u=${ url }`,
			x: `https://twitter.com/intent/tweet?text=${ title }&url=${ url }`,
			linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${ url }`,
			pinterest: `https://pinterest.com/pin/create/button/?url=${ url }&media=${ image }&description=${ title }`,
			telegram: `https://t.me/share/url?url=${ url }&text=${ title }`,
			whatsapp: `https://wa.me/?text=${ text }`,
			email: `mailto:?subject=${ title }&body=${ text }`,
		};

		return urls[ platform ] || '';
	};
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

		if ( event.target.closest( '[data-product-share-toggle]' ) && sharePopover && shareToggle ) {
			const opening = sharePopover.hidden;
			sharePopover.hidden = ! opening;
			shareToggle.setAttribute( 'aria-expanded', String( opening ) );
			if ( opening ) {
				sharePopover.querySelector( 'a, button' )?.focus();
			}
			return;
		}

		const shareItem = event.target.closest( '[data-share-platform]' );
		if ( shareItem && shareMenu ) {
			event.preventDefault();
			const platform = shareItem.dataset.sharePlatform;
			const data = {
				title: shareMenu.dataset.shareTitle,
				url: shareMenu.dataset.shareUrl,
				image: shareMenu.dataset.shareImage,
			};

			if ( platform === 'copy' ) {
				navigator.clipboard?.writeText( data.url ).then( () => {
					const label = shareItem.querySelector( '[data-share-copy-label]' );
					const status = shareMenu.querySelector( '[data-share-status]' );
					if ( label ) {
						label.textContent = shareMenu.dataset.copiedLabel || 'Copied';
					}
					if ( status ) {
						status.textContent = shareMenu.dataset.copiedLabel || 'Copied';
					}
				} ).catch( () => {} );
			} else {
				const shareUrl = getShareUrl( platform, data );
				if ( platform === 'email' ) {
					window.location.href = shareUrl;
				} else if ( shareUrl ) {
					openShareWindow( shareUrl );
				}
				closeShareMenu();
			}
		}
	} );

	document.addEventListener( 'click', ( event ) => {
		if ( shareMenu && ! shareMenu.contains( event.target ) ) {
			closeShareMenu();
		}
	} );

	document.addEventListener( 'keydown', ( event ) => {
		if ( event.key === 'Escape' && sharePopover && ! sharePopover.hidden ) {
			closeShareMenu();
			shareToggle?.focus();
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
