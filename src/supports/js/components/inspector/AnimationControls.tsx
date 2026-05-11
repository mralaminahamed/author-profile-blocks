/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';
import { SelectControl, RangeControl, PanelBody } from '@wordpress/components';

interface AnimationControlsProps {
	animationType: string;
	animationDuration: number;
	hoverEffect: string;
	onChange: ( attrs: Record< string, unknown > ) => void;
	/**
	 * Singular/plural noun used in help text — e.g. "grid items",
	 * "list items", "carousel slides". Defaults to "items".
	 */
	itemLabel?: string;
}

const ANIMATION_TYPES = [
	{ value: 'none', label: __( 'None', 'author-profile-blocks' ) },
	{ value: 'fadeIn', label: __( 'Fade In', 'author-profile-blocks' ) },
	{ value: 'slideUp', label: __( 'Slide Up', 'author-profile-blocks' ) },
	{ value: 'slideDown', label: __( 'Slide Down', 'author-profile-blocks' ) },
	{ value: 'slideLeft', label: __( 'Slide Left', 'author-profile-blocks' ) },
	{ value: 'slideRight', label: __( 'Slide Right', 'author-profile-blocks' ) },
	{ value: 'scaleIn', label: __( 'Scale In', 'author-profile-blocks' ) },
	{ value: 'bounce', label: __( 'Bounce', 'author-profile-blocks' ) },
];

const HOVER_EFFECTS = [
	{ value: 'none', label: __( 'None', 'author-profile-blocks' ) },
	{ value: 'lift', label: __( 'Lift Up', 'author-profile-blocks' ) },
	{ value: 'glow', label: __( 'Glow', 'author-profile-blocks' ) },
	{ value: 'scale', label: __( 'Scale', 'author-profile-blocks' ) },
	{ value: 'rotate', label: __( 'Rotate', 'author-profile-blocks' ) },
	{ value: 'shadow', label: __( 'Shadow', 'author-profile-blocks' ) },
];

export function AnimationControls( {
	animationType,
	animationDuration,
	hoverEffect,
	onChange,
	itemLabel = __( 'items', 'author-profile-blocks' ),
}: AnimationControlsProps ) {
	return (
		<PanelBody
			title={ __( 'Animation & Effects', 'author-profile-blocks' ) }
			initialOpen={ false }
		>
			<SelectControl
				label={ __( 'Entrance Animation', 'author-profile-blocks' ) }
				value={ animationType }
				options={ ANIMATION_TYPES }
				onChange={ ( value ) => onChange( { animationType: value } ) }
				help={ sprintf(
					/* translators: %s: items / slides / etc. */
					__( 'Choose how %s appear when loaded', 'author-profile-blocks' ),
					itemLabel,
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
				options={ HOVER_EFFECTS }
				onChange={ ( value ) => onChange( { hoverEffect: value } ) }
				help={ sprintf(
					/* translators: %s: items / slides / etc. */
					__( 'Visual effect when hovering over %s', 'author-profile-blocks' ),
					itemLabel,
				) }
			/>
		</PanelBody>
	);
}
