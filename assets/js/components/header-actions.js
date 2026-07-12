(() => {
	'use strict';

	const popupSelector = '[data-header-popup]';
	const cartConfig = window.kanapkaCartActions || {};
	const quantityTimers = new Map();
	const quantityRequests = new Map();

	const closePopup = (popup, restoreFocus = false) => {
		if (!popup) {
			return;
		}

		const button = popup.querySelector('[data-header-popup-button]');
		const panel = popup.querySelector('[data-header-popup-panel]');

		if (!button || !panel) {
			return;
		}

		button.setAttribute('aria-expanded', 'false');
		panel.hidden = true;
		popup.removeAttribute('data-open');

		if (restoreFocus) {
			button.focus();
		}
	};

	const closeAll = (except = null) => {
		document.querySelectorAll(popupSelector).forEach((popup) => {
			if (popup !== except) {
				closePopup(popup);
			}
		});
	};

	const openPopup = (popup, focusPanel = false) => {
		const button = popup?.querySelector('[data-header-popup-button]');
		const panel = popup?.querySelector('[data-header-popup-panel]');

		if (!button || !panel) {
			return;
		}

		closeAll(popup);
		button.setAttribute('aria-expanded', 'true');
		panel.hidden = false;
		popup.setAttribute('data-open', '');

		if (focusPanel) {
			const focusTarget = panel.querySelector('input, a, button');
			focusTarget?.focus();
		}
	};

	document.addEventListener('click', (event) => {
		const button = event.target.closest('[data-header-popup-button]');
		const closeButton = event.target.closest('[data-header-popup-close]');
		const removeButton = event.target.closest('.header-mini-cart .remove_from_cart_button');

		if (removeButton && cartConfig.wcAjaxUrl) {
			event.preventDefault();
			event.stopImmediatePropagation();

			if (removeButton.classList.contains('is-loading')) {
				return;
			}

			const item = removeButton.closest('.woocommerce-mini-cart-item');
			const originalMarkup = removeButton.innerHTML;
			const endpoint = cartConfig.wcAjaxUrl.replace('%%endpoint%%', 'remove_from_cart');
			const body = new URLSearchParams({ cart_item_key: removeButton.dataset.cart_item_key || '' });

			removeButton.classList.add('is-loading');
			removeButton.setAttribute('aria-disabled', 'true');
			removeButton.innerHTML = cartConfig.loaderIcon || originalMarkup;
			item?.classList.add('is-removing');

			window.fetch(endpoint, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
				body,
			}).then((response) => response.json()).then((result) => {
				if (result.error || !result.fragments) {
					throw new Error('Invalid cart response');
				}

				Object.entries(result.fragments).forEach(([selector, html]) => {
					document.querySelectorAll(selector).forEach((element) => {
						element.outerHTML = html;
					});
				});

				openUpdatedCart();
			}).catch(() => {
				removeButton.innerHTML = originalMarkup;
				removeButton.classList.remove('is-loading');
				removeButton.removeAttribute('aria-disabled');
				item?.classList.remove('is-removing');
			});

			return;
		}

		if (button) {
			const popup = button.closest(popupSelector);
			const expanded = button.getAttribute('aria-expanded') === 'true';

			expanded ? closePopup(popup) : openPopup(popup, popup?.dataset.headerPopup === 'search');
			return;
		}

		if (closeButton) {
			closePopup(closeButton.closest(popupSelector), true);
			return;
		}

		if (!event.target.closest(popupSelector)) {
			closeAll();
		}
	});

	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape') {
			const openPopupElement = document.querySelector(`${popupSelector}[data-open]`);
			closePopup(openPopupElement, true);
		}
	});

	const openUpdatedCart = () => {
		window.setTimeout(() => {
			openPopup(document.querySelector('[data-header-popup="cart"]'));
		}, 50);
	};

	const replaceCartFragments = (fragments) => {
		Object.entries(fragments || {}).forEach(([selector, html]) => {
			document.querySelectorAll(selector).forEach((element) => {
				element.outerHTML = html;
			});
		});
	};

	const updateMiniCartQuantity = (input) => {
		const cartItemKey = input.dataset.cartItemKey || '';
		const previousQuantity = Math.max(1, Number.parseInt(input.dataset.previousQuantity, 10) || 1);
		const maxQuantity = Number.parseInt(input.max, 10) || Number.POSITIVE_INFINITY;
		const quantity = Math.min(maxQuantity, Math.max(1, Number.parseInt(input.value, 10) || previousQuantity));
		const item = input.closest('.woocommerce-mini-cart-item');

		input.value = quantity;
		quantityRequests.get(cartItemKey)?.abort();

		const controller = new AbortController();
		quantityRequests.set(cartItemKey, controller);
		input.disabled = true;
		item?.classList.add('is-updating');

		const body = new URLSearchParams({
			action: 'kanapka_update_mini_cart_quantity',
			nonce: cartConfig.quantityNonce,
			cart_item_key: cartItemKey,
			quantity: String(quantity),
		});

		window.fetch(cartConfig.ajaxUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
			body,
			signal: controller.signal,
		}).then((response) => response.json()).then((result) => {
			if (!result.success || !result.data?.fragments) {
				throw new Error('Invalid cart response');
			}

			replaceCartFragments(result.data.fragments);
			openUpdatedCart();

			window.setTimeout(() => {
				const escapedKey = window.CSS?.escape ? window.CSS.escape(cartItemKey) : cartItemKey;
				const updatedInput = document.querySelector(`[data-mini-cart-quantity][data-cart-item-key="${escapedKey}"]`);
				updatedInput?.focus();
				updatedInput?.select();
			}, 100);
		}).catch((error) => {
			if (error.name !== 'AbortError') {
				input.value = previousQuantity;
				input.disabled = false;
				item?.classList.remove('is-updating');
			}
		}).finally(() => {
			if (quantityRequests.get(cartItemKey) === controller) {
				quantityRequests.delete(cartItemKey);
			}
		});
	};

	const scheduleMiniCartQuantityUpdate = (input, delay = 450) => {
		const cartItemKey = input.dataset.cartItemKey || '';
		window.clearTimeout(quantityTimers.get(cartItemKey));
		quantityTimers.set(cartItemKey, window.setTimeout(() => {
			quantityTimers.delete(cartItemKey);
			updateMiniCartQuantity(input);
		}, delay));
	};

	document.addEventListener('input', (event) => {
		const input = event.target.closest('[data-mini-cart-quantity]');

		if (input && cartConfig.ajaxUrl && cartConfig.quantityNonce) {
			scheduleMiniCartQuantityUpdate(input);
		}
	});

	document.addEventListener('change', (event) => {
		const input = event.target.closest('[data-mini-cart-quantity]');

		if (input && cartConfig.ajaxUrl && cartConfig.quantityNonce) {
			scheduleMiniCartQuantityUpdate(input, 0);
		}
	});

	if (window.jQuery) {
		window.jQuery(document.body).on('added_to_cart', openUpdatedCart);
	}

	document.body.addEventListener('wc-blocks_added_to_cart', openUpdatedCart);
})();
