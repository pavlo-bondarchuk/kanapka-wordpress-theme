(() => {
	'use strict';

	const button = document.querySelector('[data-navigation-toggle]');
	const navigation = document.querySelector('[data-navigation]');
	const header = document.querySelector('[data-site-header]');
	const mobileQuery = window.matchMedia('(max-width: 68rem)');
	const body = document.body;
	const submenuParents = navigation
		? navigation.querySelectorAll('.menu-item-has-children, .menu-item-has-mega-menu, .menu-item-has-contact-menu')
		: [];
	const config = window.kanapkaNavigation || {};
	const submenuLabel = config.openSubmenuLabel || 'Open submenu';
	const submenuCloseLabel = config.closeSubmenuLabel || 'Close submenu';
	let scrollPosition = 0;

	if (!button || !navigation) {
		return;
	}

	const updatePanelOffset = () => {
		if (!header) {
			return;
		}

		navigation.style.setProperty('--navigation-panel-top', `${header.getBoundingClientRect().bottom}px`);
	};

	const lockPage = () => {
		scrollPosition = window.scrollY || window.pageYOffset || 0;
		body.style.top = `-${scrollPosition}px`;
		body.classList.add('is-navigation-open');
	};

	const unlockPage = () => {
		body.classList.remove('is-navigation-open');
		body.style.top = '';
		window.scrollTo(0, scrollPosition);
	};

	const closeSubmenus = () => {
		submenuParents.forEach((item) => {
			const toggle = item.querySelector(':scope > .submenu-toggle');

			item.classList.remove('is-submenu-open');

			if (toggle) {
				toggle.setAttribute('aria-expanded', 'false');
				toggle.setAttribute('aria-label', submenuLabel);
			}
		});
	};

	const closeNavigation = () => {
		button.setAttribute('aria-expanded', 'false');
		navigation.removeAttribute('data-open');
		closeSubmenus();
		unlockPage();
	};

	const openNavigation = () => {
		updatePanelOffset();
		button.setAttribute('aria-expanded', 'true');
		navigation.setAttribute('data-open', '');
		lockPage();
	};

	submenuParents.forEach((item) => {
		if (item.querySelector(':scope > .submenu-toggle')) {
			return;
		}

		const link = item.querySelector(':scope > a');

		if (!link) {
			return;
		}

		const toggle = document.createElement('button');
		toggle.className = 'submenu-toggle';
		toggle.type = 'button';
		toggle.setAttribute('aria-expanded', 'false');
		toggle.setAttribute('aria-label', submenuLabel);
		toggle.innerHTML = '<span aria-hidden="true"></span>';

		link.insertAdjacentElement('afterend', toggle);

		toggle.addEventListener('click', () => {
			const expanded = toggle.getAttribute('aria-expanded') === 'true';

			item.classList.toggle('is-submenu-open', !expanded);
			toggle.setAttribute('aria-expanded', String(!expanded));
			toggle.setAttribute('aria-label', expanded ? submenuLabel : submenuCloseLabel);
		});
	});

	button.addEventListener('click', () => {
		const expanded = button.getAttribute('aria-expanded') === 'true';

		if (expanded) {
			closeNavigation();
			return;
		}

		openNavigation();
	});

	document.addEventListener('keydown', (event) => {
		if ('Escape' === event.key && navigation.hasAttribute('data-open')) {
			closeNavigation();
		}
	});

	window.addEventListener('resize', () => {
		if (navigation.hasAttribute('data-open')) {
			updatePanelOffset();
		}
	});

	mobileQuery.addEventListener('change', (event) => {
		if (!event.matches && navigation.hasAttribute('data-open')) {
			closeNavigation();
		}
	});
})();
