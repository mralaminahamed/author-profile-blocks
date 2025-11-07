/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	ToggleControl,
	ColorPalette,
	SelectControl,
	RangeControl,
} from '@wordpress/components';

/**
 * Advanced styling controls component for the Author Profile block
 *
 * @param {Object}   props                    Component props
 * @param {boolean}  props.gradientBackground Whether to use gradient background
 * @param {string}   props.gradientStartColor Gradient start color
 * @param {string}   props.gradientEndColor   Gradient end color
 * @param {string}   props.gradientDirection  Gradient direction
 * @param {number}   props.transformScale     Transform scale value
 * @param {number}   props.transformRotate    Transform rotate value
 * @param {number}   props.filterBrightness   Filter brightness value
 * @param {number}   props.filterContrast     Filter contrast value
 * @param {number}   props.filterSaturate     Filter saturate value
 * @param {Function} props.onChange           Callback when settings change
 * @return {JSX.Element} Advanced styling component
 */
export function AdvancedStyling( {
	gradientBackground,
	gradientStartColor,
	gradientEndColor,
	gradientDirection,
	transformScale,
	transformRotate,
	filterBrightness,
	filterContrast,
	filterSaturate,
	onChange,
} ) {
	const gradientDirections = [
		{ value: 'to bottom', label: __( 'Top to Bottom', 'author-profile-blocks' ) },
		{ value: 'to right', label: __( 'Left to Right', 'author-profile-blocks' ) },
		{ value: 'to bottom right', label: __( 'Top-Left to Bottom-Right', 'author-profile-blocks' ) },
		{ value: 'to bottom left', label: __( 'Top-Right to Bottom-Left', 'author-profile-blocks' ) },
		{ value: '45deg', label: __( '45° Angle', 'author-profile-blocks' ) },
		{ value: '135deg', label: __( '135° Angle', 'author-profile-blocks' ) },
	];

	return (
		<PanelBody
			title={ __( 'Advanced Styling', 'author-profile-blocks' ) }
			initialOpen={ false }
		>
			{ /* Gradient Background */ }
			<ToggleControl
				label={ __( 'Gradient Background', 'author-profile-blocks' ) }
				checked={ gradientBackground }
				onChange={ ( value ) => onChange( { gradientBackground: value } ) }
				help={ __(
					'Apply a gradient background instead of solid color',
					'author-profile-blocks',
				) }
			/>

			{ gradientBackground && (
				<>
					<div style={ { marginBottom: '16px' } }>
						<label style={ { display: 'block', marginBottom: '8px', fontWeight: '600' } }>
							{ __( 'Start Color', 'author-profile-blocks' ) }
						</label>
						<ColorPalette
							value={ gradientStartColor }
							onChange={ ( value ) => onChange( { gradientStartColor: value } ) }
							clearable={ false }
						/>
					</div>

					<div style={ { marginBottom: '16px' } }>
						<label style={ { display: 'block', marginBottom: '8px', fontWeight: '600' } }>
							{ __( 'End Color', 'author-profile-blocks' ) }
						</label>
						<ColorPalette
							value={ gradientEndColor }
							onChange={ ( value ) => onChange( { gradientEndColor: value } ) }
							clearable={ false }
						/>
					</div>

					<SelectControl
						label={ __( 'Gradient Direction', 'author-profile-blocks' ) }
						value={ gradientDirection }
						options={ gradientDirections }
						onChange={ ( value ) => onChange( { gradientDirection: value } ) }
					/>
				</>
			) }

			{ /* Transform Controls */ }
			<div style={ { marginTop: '24px', paddingTop: '16px', borderTop: '1px solid #ddd' } }>
				<h4 style={ { margin: '0 0 16px 0', fontSize: '14px', fontWeight: '600' } }>
					{ __( 'Transform Effects', 'author-profile-blocks' ) }
				</h4>

				<RangeControl
					label={ __( 'Scale', 'author-profile-blocks' ) }
					value={ transformScale }
					onChange={ ( value ) => onChange( { transformScale: value } ) }
					min={ 0.5 }
					max={ 2 }
					step={ 0.1 }
					help={ __( 'Scale the entire block (1 = normal size)', 'author-profile-blocks' ) }
				/>

				<RangeControl
					label={ __( 'Rotation', 'author-profile-blocks' ) }
					value={ transformRotate }
					onChange={ ( value ) => onChange( { transformRotate: value } ) }
					min={ -180 }
					max={ 180 }
					step={ 5 }
					help={ __( 'Rotate the block in degrees', 'author-profile-blocks' ) }
				/>
			</div>

			{ /* CSS Filters */ }
			<div style={ { marginTop: '24px', paddingTop: '16px', borderTop: '1px solid #ddd' } }>
				<h4 style={ { margin: '0 0 16px 0', fontSize: '14px', fontWeight: '600' } }>
					{ __( 'CSS Filters', 'author-profile-blocks' ) }
				</h4>

				<RangeControl
					label={ __( 'Brightness', 'author-profile-blocks' ) }
					value={ filterBrightness }
					onChange={ ( value ) => onChange( { filterBrightness: value } ) }
					min={ 0 }
					max={ 200 }
					step={ 5 }
					help={ __( 'Adjust brightness (100 = normal)', 'author-profile-blocks' ) }
				/>

				<RangeControl
					label={ __( 'Contrast', 'author-profile-blocks' ) }
					value={ filterContrast }
					onChange={ ( value ) => onChange( { filterContrast: value } ) }
					min={ 0 }
					max={ 200 }
					step={ 5 }
					help={ __( 'Adjust contrast (100 = normal)', 'author-profile-blocks' ) }
				/>

				<RangeControl
					label={ __( 'Saturation', 'author-profile-blocks' ) }
					value={ filterSaturate }
					onChange={ ( value ) => onChange( { filterSaturate: value } ) }
					min={ 0 }
					max={ 200 }
					step={ 5 }
					help={ __( 'Adjust saturation (100 = normal)', 'author-profile-blocks' ) }
				/>
			</div>
		</PanelBody>
	);
}
