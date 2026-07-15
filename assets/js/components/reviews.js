(() => {
	const replyLinks = document.querySelectorAll('.reviews-page .comment-reply-link');

	if (!replyLinks.length) {
		return;
	}

	const scrollToReplyForm = () => {
		const respond = document.getElementById('respond');

		if (!respond) {
			return;
		}

		window.requestAnimationFrame(() => {
			const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

			respond.scrollIntoView({
				behavior: reduceMotion ? 'auto' : 'smooth',
				block: 'center',
			});

			window.setTimeout(() => {
				const commentField = respond.querySelector('#comment');

				if (commentField) {
					commentField.focus({ preventScroll: true });
				}
			}, reduceMotion ? 0 : 350);
		});
	};

	replyLinks.forEach((link) => {
		link.addEventListener('click', () => {
			window.setTimeout(scrollToReplyForm, 0);
		});
	});
})();
