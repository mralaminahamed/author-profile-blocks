/**
 * WordPress dependencies
 */
import domReady from '@wordpress/dom-ready';

/**
 * Initialize the frontend functionality.
 */
domReady(() => {
    // Find all instances of the block
    const authorBlocks = document.querySelectorAll('.wp-block-wp-author-showcase-author-profile');

    if (!authorBlocks.length) {
        return;
    }

    // Add any frontend interactivity as needed
    authorBlocks.forEach((block) => {
        // Adding a click handler to email links to track clicks
        const emailLinks = block.querySelectorAll('.wpas-author-email a');
        emailLinks.forEach((link) => {
            link.addEventListener('click', (event) => {
                // Optional: Track email clicks
                if (typeof window.gtag === 'function') {
                    window.gtag('event', 'click', {
                        'event_category': 'Author Profile',
                        'event_label': 'Email Link',
                        'value': link.textContent.trim()
                    });
                }
            });
        });
    });
});
