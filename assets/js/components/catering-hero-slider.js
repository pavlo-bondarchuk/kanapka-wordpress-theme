(() => {
	'use strict';

	const slider = document.querySelector('[data-catering-hero-slider]');
	if (!slider) return;

	const track = slider.querySelector('[data-catering-hero-track]');
	const slides = [...slider.querySelectorAll('[data-catering-hero-slide]')];
	const dots = [...slider.querySelectorAll('[data-catering-hero-dot]')];
	let current = 0;
	let touchStart = 0;

	const show = (index) => {
		current = (index + slides.length) % slides.length;
		track.style.setProperty('--catering-hero-slide', current);
		slides.forEach((slide, slideIndex) => slide.setAttribute('aria-hidden', String(slideIndex !== current)));
		dots.forEach((dot, dotIndex) => dot.setAttribute('aria-current', String(dotIndex === current)));
	};

	slider.querySelector('[data-catering-hero-previous]')?.addEventListener('click', () => show(current - 1));
	slider.querySelector('[data-catering-hero-next]')?.addEventListener('click', () => show(current + 1));
	dots.forEach((dot) => dot.addEventListener('click', () => show(Number(dot.dataset.cateringHeroDot))));

	slider.addEventListener('touchstart', (event) => {
		touchStart = event.changedTouches[0].clientX;
	}, {passive: true});

	slider.addEventListener('touchend', (event) => {
		const distance = event.changedTouches[0].clientX - touchStart;
		if (Math.abs(distance) > 40) show(current + (distance < 0 ? 1 : -1));
	}, {passive: true});
})();
