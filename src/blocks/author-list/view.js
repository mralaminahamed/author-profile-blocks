/**
 * Author List block frontend script.
 *
 * Handles frontend interactions for the Author List block.
 */

/**
 * Initialize frontend functionality when DOM is ready.
 */
document.addEventListener('DOMContentLoaded', () => {
	initAuthorListBlocks();
});

/**
 * Initialize all Author List blocks on the page.
 */
function initAuthorListBlocks() {
	const authorListBlocks = document.querySelectorAll(
		'.wp-block-author-profile-blocks-author-list'
	);

	if (!authorListBlocks.length) {
		return;
	}

	authorListBlocks.forEach((block) => {
		// Initialize hover effects if present
		const hoverItems = block.querySelectorAll('.has-hover-effect');
		if (hoverItems.length) {
			initHoverEffects(hoverItems);
		}

		// Initialize any interactive elements
		initInteractiveElements(block);
	});
}

/**
 * Initialize hover effects for list items.
 *
 * @param {NodeList} items List items with hover effects.
 */
function initHoverEffects(items) {
	items.forEach((item) => {
		item.addEventListener('mouseenter', () => {
			// Add any additional hover effect classes or attributes
			item.classList.add('is-hovered');
		});

		item.addEventListener('mouseleave', () => {
			// Remove hover effect classes or attributes
			item.classList.remove('is-hovered');
		});
	});
}

/**
 * Initialize interactive elements within the block.
 *
 * @param {Element} block The Author List block element.
 */
function initInteractiveElements(block) {
	// Initialize social link interactions
	const socialLinks = block.querySelectorAll('.apbl-social-item a');
	if (socialLinks.length) {
		socialLinks.forEach((link) => {
			// Open in new window
			link.setAttribute('target', '_blank');
			link.setAttribute('rel', 'noopener noreferrer');

			link.addEventListener('click', (e) => {
				// Track social clicks if analytics is available
				if (typeof window.apbTrackEvent === 'function') {
					const network = link
						.closest('.apbl-social-item')
						.className.split('apbl-social-')[1];
					window.apbTrackEvent('social_click', { network });
				}
			});
		});
	}

	// Add any other interactive element initializations here
}
