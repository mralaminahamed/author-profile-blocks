/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	RangeControl,
	BaseControl,
	ColorPalette,
	ToggleControl,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import { PresetStyles } from '../PresetStyles';
import { AdvancedStyling } from '../AdvancedStyling';

/**
 * StylePanel component for styling settings in the InspectorControls
 *
 * @param {Object}   props               Component props
 * @param {Object}   props.attributes    Block attributes
 * @param {Function} props.setAttributes Function to set block attributes
 * @return {JSX.Element} Style panel component
 */
const StylePanel = ( { attributes, setAttributes } ) => {
	const {
		backgroundColor,
		enableShadow,
		enableBorder,
		borderColor,
		borderWidth,
		enableRounded,
		padding,
		itemSpacing,
		// Advanced styling attributes
		gradientBackground,
		gradientStartColor,
		gradientEndColor,
		gradientDirection,
		transformScale,
		transformRotate,
		filterBrightness,
		filterContrast,
		filterSaturate,
		boxShadow,
		boxShadowColor,
		boxShadowBlur,
		boxShadowSpread,
		boxShadowHorizontal,
		boxShadowVertical,
		borderRadius,
	} = attributes;

	return (
		<>
			<PresetStyles
				onApplyPreset={ ( settings ) => {
					// Apply all preset settings at once
					setAttributes( settings );
				} }
			/>

			<PanelBody title={ __( 'Grid Background', 'author-profile-blocks' ) }>
				<BaseControl
					label={ __( 'Background Color', 'author-profile-blocks' ) }
				>
					<ColorPalette
						value={ backgroundColor }
						onChange={ ( value ) =>
							setAttributes( { backgroundColor: value } )
						}
					/>
				</BaseControl>
			</PanelBody>

			<AdvancedStyling
				gradientBackground={ gradientBackground }
				gradientStartColor={ gradientStartColor }
				gradientEndColor={ gradientEndColor }
				gradientDirection={ gradientDirection }
				transformScale={ transformScale }
				transformRotate={ transformRotate }
				filterBrightness={ filterBrightness }
				filterContrast={ filterContrast }
				filterSaturate={ filterSaturate }
				onChange={ setAttributes }
			/>

			<PanelBody
				title={ __( 'Grid Appearance', 'author-profile-blocks' ) }
				initialOpen={ false }
			>
				<RangeControl
					label={ __( 'Item Padding', 'author-profile-blocks' ) }
					value={ padding }
					onChange={ ( value ) => setAttributes( { padding: value } ) }
					min={ 0 }
					max={ 50 }
				/>

				<RangeControl
					label={ __( 'Item Spacing', 'author-profile-blocks' ) }
					value={ itemSpacing }
					onChange={ ( value ) => setAttributes( { itemSpacing: value } ) }
					min={ 0 }
					max={ 50 }
				/>

				<ToggleControl
					label={ __( 'Enable Shadow', 'author-profile-blocks' ) }
					checked={ enableShadow }
					onChange={ () => setAttributes( { enableShadow: ! enableShadow } ) }
				/>

				<ToggleControl
					label={ __( 'Enable Border', 'author-profile-blocks' ) }
					checked={ enableBorder }
					onChange={ () => setAttributes( { enableBorder: ! enableBorder } ) }
				/>

				{ enableBorder && (
					<>
						<RangeControl
							label={ __( 'Border Width', 'author-profile-blocks' ) }
							value={ borderWidth }
							onChange={ ( value ) => setAttributes( { borderWidth: value } ) }
							min={ 1 }
							max={ 10 }
						/>

						<BaseControl
							label={ __( 'Border Color', 'author-profile-blocks' ) }
						>
							<ColorPalette
								value={ borderColor }
								onChange={ ( value ) =>
									setAttributes( { borderColor: value } )
								}
							/>
						</BaseControl>
					</>
				) }

				<ToggleControl
					label={ __( 'Rounded Corners', 'author-profile-blocks' ) }
					checked={ enableRounded }
					onChange={ () => setAttributes( { enableRounded: ! enableRounded } ) }
				/>

				{ enableRounded && (
					<RangeControl
						label={ __( 'Border Radius', 'author-profile-blocks' ) }
						value={ borderRadius }
						onChange={ ( value ) => setAttributes( { borderRadius: value } ) }
						min={ 0 }
						max={ 50 }
					/>
				) }
			</PanelBody>

			<PanelBody
				title={ __( 'Box Shadow', 'author-profile-blocks' ) }
				initialOpen={ false }
			>
				<ToggleControl
					label={ __( 'Enable Box Shadow', 'author-profile-blocks' ) }
					checked={ boxShadow }
					onChange={ () => setAttributes( { boxShadow: ! boxShadow } ) }
				/>

				{ boxShadow && (
					<>
						<BaseControl
							label={ __( 'Shadow Color', 'author-profile-blocks' ) }
						>
							<ColorPalette
								value={ boxShadowColor }
								onChange={ ( value ) =>
									setAttributes( { boxShadowColor: value } )
								}
							/>
						</BaseControl>

						<RangeControl
							label={ __( 'Horizontal Offset', 'author-profile-blocks' ) }
							value={ boxShadowHorizontal }
							onChange={ ( value ) =>
								setAttributes( { boxShadowHorizontal: value } )
							}
							min={ -50 }
							max={ 50 }
						/>

						<RangeControl
							label={ __( 'Vertical Offset', 'author-profile-blocks' ) }
							value={ boxShadowVertical }
							onChange={ ( value ) =>
								setAttributes( { boxShadowVertical: value } )
							}
							min={ -50 }
							max={ 50 }
						/>

						<RangeControl
							label={ __( 'Blur Radius', 'author-profile-blocks' ) }
							value={ boxShadowBlur }
							onChange={ ( value ) =>
								setAttributes( { boxShadowBlur: value } )
							}
							min={ 0 }
							max={ 100 }
						/>

						<RangeControl
							label={ __( 'Spread', 'author-profile-blocks' ) }
							value={ boxShadowSpread }
							onChange={ ( value ) =>
								setAttributes( { boxShadowSpread: value } )
							}
							min={ -50 }
							max={ 50 }
						/>
					</>
				) }
			</PanelBody>
		</>
	);
};

export default StylePanel;
