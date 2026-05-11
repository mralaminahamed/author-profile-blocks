/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	SelectControl,
	PanelBody,
	Notice,
} from '@wordpress/components';
import { useEffect } from '@wordpress/element';

/**
 * Advanced typography controls component for the Author Grid block
 *
 * @param {Object}   props              Component props
 * @param {string}   props.googleFont   Selected Google Font
 * @param {string}   props.fontSizeUnit Font size unit (px, em, rem)
 * @param {Function} props.onChange     Callback when settings change
 * @return {JSX.Element} Advanced typography component
 */
export function AdvancedTypography( {
	googleFont,
	fontSizeUnit,
	onChange,
} ) {
	// Popular Google Fonts for selection
	const popularFonts = [
		{ value: '', label: __( 'Default (Theme Font)', 'author-profile-blocks' ) },
		{ value: 'Roboto', label: 'Roboto' },
		{ value: 'Open Sans', label: 'Open Sans' },
		{ value: 'Lato', label: 'Lato' },
		{ value: 'Montserrat', label: 'Montserrat' },
		{ value: 'Poppins', label: 'Poppins' },
		{ value: 'Nunito', label: 'Nunito' },
		{ value: 'Inter', label: 'Inter' },
		{ value: 'Work Sans', label: 'Work Sans' },
		{ value: 'Playfair Display', label: 'Playfair Display' },
		{ value: 'Merriweather', label: 'Merriweather' },
		{ value: 'Source Sans Pro', label: 'Source Sans Pro' },
		{ value: 'Fira Sans', label: 'Fira Sans' },
		{ value: 'Crimson Text', label: 'Crimson Text' },
	];

	const fontSizeUnits = [
		{ value: 'px', label: 'px' },
		{ value: 'em', label: 'em' },
		{ value: 'rem', label: 'rem' },
		{ value: '%', label: '%' },
	];

	useEffect( () => {
		// Load Google Font if selected
		if ( googleFont && googleFont !== '' ) {
			loadGoogleFont( googleFont );
		}
	}, [ googleFont ] );

	const loadGoogleFont = ( fontName ) => {
		if ( ! fontName ) {
			return;
		}

		// Remove existing Google Fonts link if present
		const existingLink = document.querySelector( 'link[href*="fonts.googleapis.com"]' );
		if ( existingLink ) {
			existingLink.remove();
		}

		// Add new Google Font
		const link = document.createElement( 'link' );
		link.href = `https://fonts.googleapis.com/css2?family=${ encodeURIComponent( fontName ) }:wght@300;400;500;600;700&display=swap`;
		link.rel = 'stylesheet';
		document.head.appendChild( link );
	};

	return (
		<PanelBody
			title={ __( 'Advanced Typography', 'author-profile-blocks' ) }
			initialOpen={ false }
		>
			<SelectControl
				label={ __( 'Google Font', 'author-profile-blocks' ) }
				value={ googleFont }
				options={ popularFonts }
				onChange={ ( value ) => onChange( { googleFont: value } ) }
				help={ __(
					'Choose from popular Google Fonts to enhance grid typography',
					'author-profile-blocks',
				) }
			/>

			{ googleFont && (
				<Notice status="info" isDismissible={ false }>
					{ __( 'Font loaded — applies to author names.', 'author-profile-blocks' ) }
				</Notice>
			) }

			<SelectControl
				label={ __( 'Font Size Unit', 'author-profile-blocks' ) }
				value={ fontSizeUnit }
				options={ fontSizeUnits }
				onChange={ ( value ) => onChange( { fontSizeUnit: value } ) }
				help={ __( 'em/rem for responsive sizing', 'author-profile-blocks' ) }
			/>
		</PanelBody>
	);
}
