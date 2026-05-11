import { __ } from '@wordpress/i18n';
import { SelectControl, PanelBody, Notice } from '@wordpress/components';
import { useEffect } from '@wordpress/element';

interface AdvancedTypographyProps {
	googleFont: string;
	fontSizeUnit: string;
	onChange: ( attrs: Record< string, unknown > ) => void;
}

const GOOGLE_FONTS = [
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

const FONT_SIZE_UNITS = [
	{ value: 'px', label: 'px' },
	{ value: 'em', label: 'em' },
	{ value: 'rem', label: 'rem' },
	{ value: '%', label: '%' },
];

function loadGoogleFont( fontName: string ) {
	if ( ! fontName ) return;
	const existingLink = document.querySelector( 'link[href*="fonts.googleapis.com"]' );
	if ( existingLink ) existingLink.remove();
	const link = document.createElement( 'link' );
	link.href = `https://fonts.googleapis.com/css2?family=${ encodeURIComponent( fontName ) }:wght@300;400;500;600;700&display=swap`;
	link.rel = 'stylesheet';
	document.head.appendChild( link );
}

export function AdvancedTypography( { googleFont, fontSizeUnit, onChange }: AdvancedTypographyProps ) {
	useEffect( () => {
		if ( googleFont ) loadGoogleFont( googleFont );
	}, [ googleFont ] );

	return (
		<PanelBody title={ __( 'Typography', 'author-profile-blocks' ) } initialOpen={ false }>
			<SelectControl
				label={ __( 'Google Font', 'author-profile-blocks' ) }
				value={ googleFont }
				options={ GOOGLE_FONTS }
				onChange={ ( value ) => onChange( { googleFont: value } ) }
			/>

			{ googleFont && (
				<Notice status="info" isDismissible={ false }>
					{ __( 'Font loaded — applies to author names.', 'author-profile-blocks' ) }
				</Notice>
			) }

			<SelectControl
				label={ __( 'Font Size Unit', 'author-profile-blocks' ) }
				value={ fontSizeUnit }
				options={ FONT_SIZE_UNITS }
				onChange={ ( value ) => onChange( { fontSizeUnit: value } ) }
				help={ __( 'em/rem for responsive sizing', 'author-profile-blocks' ) }
			/>
		</PanelBody>
	);
}
