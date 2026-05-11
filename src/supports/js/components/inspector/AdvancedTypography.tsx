/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Notice, PanelBody, SelectControl } from '@wordpress/components';
import type { ReactNode } from 'react';

interface AdvancedTypographyProps {
	googleFont: string;
	fontSizeUnit: string;
	onChange: ( attrs: Record< string, unknown > ) => void;
	/** Panel title. Defaults to "Typography". */
	title?: string;
	/** Help text for the Google Font SelectControl. Omit for no help text. */
	googleFontHelp?: string;
	/** Notice shown below the font picker once a font is selected. */
	fontNotice?: string;
	/** Help text for the font-size-unit SelectControl. */
	fontSizeUnitHelp?: string;
	/** Optional render slot below the controls — used by author-list for tips. */
	tips?: ReactNode;
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

export function AdvancedTypography( {
	googleFont,
	fontSizeUnit,
	onChange,
	title = __( 'Typography', 'author-profile-blocks' ),
	googleFontHelp,
	fontNotice = __( 'Font loaded — applies to author names.', 'author-profile-blocks' ),
	fontSizeUnitHelp = __( 'em/rem for responsive sizing', 'author-profile-blocks' ),
	tips,
}: AdvancedTypographyProps ) {
	return (
		<PanelBody title={ title } initialOpen={ false }>
			<SelectControl
				label={ __( 'Google Font', 'author-profile-blocks' ) }
				value={ googleFont }
				options={ GOOGLE_FONTS }
				onChange={ ( value ) => onChange( { googleFont: value } ) }
				help={ googleFontHelp }
			/>

			{ googleFont && (
				<Notice status="info" isDismissible={ false }>
					{ fontNotice }
				</Notice>
			) }

			<SelectControl
				label={ __( 'Font Size Unit', 'author-profile-blocks' ) }
				value={ fontSizeUnit }
				options={ FONT_SIZE_UNITS }
				onChange={ ( value ) => onChange( { fontSizeUnit: value } ) }
				help={ fontSizeUnitHelp }
			/>

			{ tips }
		</PanelBody>
	);
}
