/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	SelectControl,
	RangeControl,
	PanelBody,
} from '@wordpress/components';

/**
 * Animation controls component for the Author List block
 *
 * @param {Object}   props                   Component props
 * @param {string}   props.animationType     Current animation type
 * @param {number}   props.animationDuration Animation duration in ms
 * @param {string}   props.hoverEffect       Current hover effect
 * @param {Function} props.onChange          Callback when settings change
 * @return {JSX.Element} Animation controls component
 */
export function AnimationControls( {
	animationType,
	animationDuration,
	hoverEffect,
	onChange,
} ) {
	const animationTypes = [
		{ value: 'none', label: __( 'None', 'author-profile-blocks' ) },
		{ value: 'fadeIn', label: __( 'Fade In', 'author-profile-blocks' ) },
		{ value: 'slideUp', label: __( 'Slide Up', 'author-profile-blocks' ) },
		{ value: 'slideDown', label: __( 'Slide Down', 'author-profile-blocks' ) },
		{ value: 'slideLeft', label: __( 'Slide Left', 'author-profile-blocks' ) },
		{ value: 'slideRight', label: __( 'Slide Right', 'author-profile-blocks' ) },
		{ value: 'scaleIn', label: __( 'Scale In', 'author-profile-blocks' ) },
		{ value: 'bounce', label: __( 'Bounce', 'author-profile-blocks' ) },
	];

	const hoverEffects = [
		{ value: 'none', label: __( 'None', 'author-profile-blocks' ) },
		{ value: 'lift', label: __( 'Lift Up', 'author-profile-blocks' ) },
		{ value: 'glow', label: __( 'Glow', 'author-profile-blocks' ) },
		{ value: 'scale', label: __( 'Scale', 'author-profile-blocks' ) },
		{ value: 'rotate', label: __( 'Rotate', 'author-profile-blocks' ) },
		{ value: 'shadow', label: __( 'Shadow', 'author-profile-blocks' ) },
	];

	return (
		<PanelBody
			title={ __( 'Animation & Effects', 'author-profile-blocks' ) }
			initialOpen={ false }
		>
			<SelectControl
				label={ __( 'Entrance Animation', 'author-profile-blocks' ) }
				value={ animationType }
				options={ animationTypes }
				onChange={ ( value ) => onChange( { animationType: value } ) }
				help={ __(
					'Choose how list items appear when loaded',
					'author-profile-blocks',
				) }
			/>

			{ animationType !== 'none' && (
				<RangeControl
					label={ __( 'Animation Duration', 'author-profile-blocks' ) }
					value={ animationDuration }
					onChange={ ( value ) => onChange( { animationDuration: value } ) }
					min={ 100 }
					max={ 2000 }
					step={ 50 }
					help={ __( 'Duration in milliseconds', 'author-profile-blocks' ) }
				/>
			) }

			<SelectControl
				label={ __( 'Hover Effect', 'author-profile-blocks' ) }
				value={ hoverEffect }
				options={ hoverEffects }
				onChange={ ( value ) => onChange( { hoverEffect: value } ) }
				help={ __(
					'Visual effect when hovering over list items',
					'author-profile-blocks',
				) }
			/>
		</PanelBody>
	);
}