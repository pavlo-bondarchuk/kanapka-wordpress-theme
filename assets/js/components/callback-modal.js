(() => {
	'use strict';

	const modal = document.querySelector('[data-callback-modal]');
	const dialog = modal?.querySelector('[data-callback-modal-dialog]');
	let opener = null;

	if (!modal || !dialog) {
		return;
	}

	const focusableSelector = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

	const close = () => {
		modal.hidden = true;
		document.body.classList.remove('is-callback-modal-open');
		opener?.focus();
		opener = null;
	};

	const open = (trigger) => {
		const headerPopup = trigger.closest('[data-header-popup]');

		if (headerPopup) {
			headerPopup.querySelector('[data-header-popup-button]')?.setAttribute('aria-expanded', 'false');
			const headerPanel = headerPopup.querySelector('[data-header-popup-panel]');

			if (headerPanel) {
				headerPanel.hidden = true;
			}

			headerPopup.removeAttribute('data-open');
		}

		opener = trigger;
		modal.hidden = false;
		document.body.classList.add('is-callback-modal-open');
		dialog.focus();
	};

	document.addEventListener('click', (event) => {
		const trigger = event.target.closest('[data-callback-modal-open]');

		if (trigger) {
			event.preventDefault();
			open(trigger);
			return;
		}

		if (!modal.hidden && event.target.closest('[data-callback-modal-close]')) {
			close();
		}
	});

	document.addEventListener('keydown', (event) => {
		if (modal.hidden) {
			return;
		}

		if (event.key === 'Escape') {
			event.preventDefault();
			close();
			return;
		}

		if (event.key !== 'Tab') {
			return;
		}

		const focusable = [...dialog.querySelectorAll(focusableSelector)].filter((element) => element.offsetParent !== null);

		if (!focusable.length) {
			event.preventDefault();
			dialog.focus();
			return;
		}

		const first = focusable[0];
		const last = focusable[focusable.length - 1];

		if (event.shiftKey && document.activeElement === first) {
			event.preventDefault();
			last.focus();
		} else if (!event.shiftKey && document.activeElement === last) {
			event.preventDefault();
			first.focus();
		}
	});
})();
