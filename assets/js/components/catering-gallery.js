(() => {
	'use strict';

	const gallery = document.querySelector('[data-catering-gallery]');
	const lightbox = document.querySelector('[data-catering-lightbox]');
	const dialog = lightbox?.querySelector('[data-catering-lightbox-dialog]');
	const image = lightbox?.querySelector('[data-catering-lightbox-image]');
	const counter = lightbox?.querySelector('[data-catering-lightbox-counter]');
	const items = gallery ? [...gallery.querySelectorAll('[data-catering-gallery-item]')] : [];
	let currentIndex = 0;
	let touchStartX = 0;

	if (!lightbox || !dialog || !image || !counter || !items.length) {
		return;
	}

	const show = (index) => {
		currentIndex = (index + items.length) % items.length;
		const item = items[currentIndex];

		image.src = item.dataset.gallerySrc || '';
		image.alt = item.dataset.galleryAlt || '';
		counter.textContent = `${currentIndex + 1} / ${items.length}`;
	};

	const open = (index) => {
		show(index);
		lightbox.hidden = false;
		document.body.classList.add('is-catering-lightbox-open');
		dialog.focus();
	};

	const close = () => {
		lightbox.hidden = true;
		document.body.classList.remove('is-catering-lightbox-open');
		image.removeAttribute('src');
		items[currentIndex]?.focus();
	};

	items.forEach((item, index) => item.addEventListener('click', () => open(index)));
	lightbox.querySelector('[data-catering-lightbox-previous]')?.addEventListener('click', () => show(currentIndex - 1));
	lightbox.querySelector('[data-catering-lightbox-next]')?.addEventListener('click', () => show(currentIndex + 1));
	lightbox.querySelectorAll('[data-catering-lightbox-close]').forEach((button) => button.addEventListener('click', close));

	dialog.addEventListener('touchstart', (event) => {
		touchStartX = event.changedTouches[0]?.clientX || 0;
	}, { passive: true });

	dialog.addEventListener('touchend', (event) => {
		const distance = (event.changedTouches[0]?.clientX || 0) - touchStartX;

		if (Math.abs(distance) > 50) {
			show(currentIndex + (distance < 0 ? 1 : -1));
		}
	}, { passive: true });

	document.addEventListener('keydown', (event) => {
		if (lightbox.hidden) {
			return;
		}

		if (event.key === 'Escape') {
			close();
		} else if (event.key === 'ArrowLeft') {
			show(currentIndex - 1);
		} else if (event.key === 'ArrowRight') {
			show(currentIndex + 1);
		} else if (event.key === 'Tab') {
			const controls = [...dialog.querySelectorAll('button')];
			const first = controls[0];
			const last = controls[controls.length - 1];

			if (event.shiftKey && document.activeElement === first) {
				event.preventDefault();
				last.focus();
			} else if (!event.shiftKey && document.activeElement === last) {
				event.preventDefault();
				first.focus();
			}
		}
	});
})();
