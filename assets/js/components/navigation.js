(() => {
	'use strict';

	const button = document.querySelector('[data-navigation-toggle]');
	const navigation = document.querySelector('[data-navigation]');

	if (!button || !navigation) {
		return;
	}

	button.addEventListener('click', () => {
		const expanded = button.getAttribute('aria-expanded') === 'true';

		button.setAttribute('aria-expanded', String(!expanded));
		navigation.toggleAttribute('data-open', !expanded);
	});
})();

