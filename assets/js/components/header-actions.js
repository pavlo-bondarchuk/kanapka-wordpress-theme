(() => {
	'use strict';

	const popupSelector = '[data-header-popup]';

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

	if (window.jQuery) {
		window.jQuery(document.body).on('added_to_cart', openUpdatedCart);
	}

	document.body.addEventListener('wc-blocks_added_to_cart', openUpdatedCart);
})();
