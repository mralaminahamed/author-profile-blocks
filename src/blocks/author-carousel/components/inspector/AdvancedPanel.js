/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PanelBody, SelectControl, RangeControl, TextControl } from '@wordpress/components';

/**
 * Advanced Panel Component
 *
 * @param {Object}   props               Component props
 * @param {Object}   props.attributes    Block attributes
 * @param {Function} props.setAttributes Set attributes function
 * @return {JSX.Element} Advanced panel
 */
export default function AdvancedPanel( { attributes, setAttributes } ) {
	const {
		animationType,
		animationDuration,
		hoverEffect,
		customCssClass,
		googleFont,
		fontSizeUnit,
		customVar1,
		customVar2,
	} = attributes;

	const googleFonts = [
		{ label: __( 'None', 'author-profile-blocks' ), value: '' },
		{ label: 'Roboto', value: 'Roboto' },
		{ label: 'Open Sans', value: 'Open Sans' },
		{ label: 'Lato', value: 'Lato' },
		{ label: 'Montserrat', value: 'Montserrat' },
		{ label: 'Poppins', value: 'Poppins' },
		{ label: 'Nunito', value: 'Nunito' },
		{ label: 'Inter', value: 'Inter' },
		{ label: 'Work Sans', value: 'Work Sans' },
		{ label: 'Playfair Display', value: 'Playfair Display' },
		{ label: 'Source Sans Pro', value: 'Source Sans Pro' },
		{ label: 'Ubuntu', value: 'Ubuntu' },
		{ label: 'Oswald', value: 'Oswald' },
		{ label: 'PT Sans', value: 'PT Sans' },
	];

	return (
		<PanelBody title={ __( 'Advanced', 'author-profile-blocks' ) }>
			<SelectControl
				label={ __( 'Entrance Animation', 'author-profile-blocks' ) }
				value={ animationType }
				options={ [
					{ label: __( 'None', 'author-profile-blocks' ), value: 'none' },
					{ label: __( 'Fade In', 'author-profile-blocks' ), value: 'fadeIn' },
					{ label: __( 'Slide Up', 'author-profile-blocks' ), value: 'slideUp' },
					{ label: __( 'Slide Down', 'author-profile-blocks' ), value: 'slideDown' },
					{ label: __( 'Slide Left', 'author-profile-blocks' ), value: 'slideLeft' },
					{ label: __( 'Slide Right', 'author-profile-blocks' ), value: 'slideRight' },
					{ label: __( 'Scale In', 'author-profile-blocks' ), value: 'scaleIn' },
					{ label: __( 'Bounce', 'author-profile-blocks' ), value: 'bounce' },
				] }
				onChange={ ( value ) =>
					setAttributes( { animationType: value } )
				}
			/>

			{ animationType !== 'none' && (
				<RangeControl
					label={ __( 'Animation Duration (ms)', 'author-profile-blocks' ) }
					value={ animationDuration }
					onChange={ ( value ) =>
						setAttributes( { animationDuration: value } )
					}
					min={ 100 }
					max={ 2000 }
					step={ 50 }
				/>
			) }

			<SelectControl
				label={ __( 'Hover Effect', 'author-profile-blocks' ) }
				value={ hoverEffect }
				options={ [
					{ label: __( 'None', 'author-profile-blocks' ), value: 'none' },
					{ label: __( 'Lift', 'author-profile-blocks' ), value: 'lift' },
					{ label: __( 'Glow', 'author-profile-blocks' ), value: 'glow' },
					{ label: __( 'Scale', 'author-profile-blocks' ), value: 'scale' },
					{ label: __( 'Rotate', 'author-profile-blocks' ), value: 'rotate' },
					{ label: __( 'Shadow', 'author-profile-blocks' ), value: 'shadow' },
				] }
				onChange={ ( value ) =>
					setAttributes( { hoverEffect: value } )
				}
			/>

			<SelectControl
				label={ __( 'Google Font', 'author-profile-blocks' ) }
				value={ googleFont }
				options={ googleFonts }
				onChange={ ( value ) =>
					setAttributes( { googleFont: value } )
				}
			/>

			<SelectControl
				label={ __( 'Font Size Unit', 'author-profile-blocks' ) }
				value={ fontSizeUnit }
				options={ [
					{ label: 'px', value: 'px' },
					{ label: 'em', value: 'em' },
					{ label: 'rem', value: 'rem' },
					{ label: '%', value: '%' },
				] }
				onChange={ ( value ) =>
					setAttributes( { fontSizeUnit: value } )
				}
			/>

			<TextControl
				label={ __( 'Custom CSS Class', 'author-profile-blocks' ) }
				value={ customCssClass }
				onChange={ ( value ) =>
					setAttributes( { customCssClass: value } )
				}
				placeholder={ __( 'Enter custom CSS class', 'author-profile-blocks' ) }
			/>

			<TextControl
				label={ __( 'Custom Variable 1', 'author-profile-blocks' ) }
				value={ customVar1 }
				onChange={ ( value ) =>
					setAttributes( { customVar1: value } )
				}
				placeholder={ __( 'Custom CSS value', 'author-profile-blocks' ) }
			/>

			<TextControl
				label={ __( 'Custom Variable 2', 'author-profile-blocks' ) }
				value={ customVar2 }
				onChange={ ( value ) =>
					setAttributes( { customVar2: value } )
				}
				placeholder={ __( 'Custom CSS value', 'author-profile-blocks' ) }
			/>
		</PanelBody>
	);
}
