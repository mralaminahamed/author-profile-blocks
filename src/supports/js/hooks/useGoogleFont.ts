/**
 * WordPress dependencies
 */
import { useEffect } from '@wordpress/element';

/**
 * Load a Google Font into the document head when fontName changes.
 *
 * Replaces any previously injected Google Fonts link so block previews
 * never accumulate multiple competing font stylesheets in the editor.
 *
 * @param fontName Font family to load. Empty/undefined skips injection.
 */
export default function useGoogleFont( fontName?: string ): void {
	useEffect( () => {
		if ( ! fontName ) {
			return;
		}

		const existing = document.querySelector(
			'link[href*="fonts.googleapis.com"]',
		);
		if ( existing ) {
			existing.remove();
		}

		const link = document.createElement( 'link' );
		link.href = `https://fonts.googleapis.com/css2?family=${ encodeURIComponent(
			fontName,
		) }:wght@300;400;500;600;700&display=swap`;
		link.rel = 'stylesheet';
		document.head.appendChild( link );
	}, [ fontName ] );
}
