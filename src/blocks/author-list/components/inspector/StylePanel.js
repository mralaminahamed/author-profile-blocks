/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	RangeControl,
	SelectControl,
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
		itemBackgroundColor,
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
		// List styling
		enableDividers,
		dividerColor,
		enableRounded,
		enableHoverEffect,
		hoverEffect,
		itemPadding,
		itemSpacing,
		boxShadow,
		boxShadowColor,
		boxShadowBlur,
		boxShadowSpread,
		boxShadowHorizontal,
		boxShadowVertical,
		borderWidth,
		borderColor,
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

			<PanelBody title={ __( 'List Background', 'author-profile-blocks' ) }>
				<BaseControl
					label={ __( 'Block Background', 'author-profile-blocks' ) }
				>
					<ColorPalette
						value={ backgroundColor }
						onChange={ ( value ) =>
							setAttributes( { backgroundColor: value } )
						}
					/>
				</BaseControl>

				<BaseControl
					label={ __( 'Item Background', 'author-profile-blocks' ) }
				>
					<ColorPalette
						value={ itemBackgroundColor }
						onChange={ ( value ) =>
							setAttributes( { itemBackgroundColor: value } )
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
				title={ __( 'List Appearance', 'author-profile-blocks' ) }
				initialOpen={ false }
			>
				<ToggleControl
					label={ __( 'Show Dividers', 'author-profile-blocks' ) }
					checked={ enableDividers }
					onChange={ () => setAttributes( { enableDividers: ! enableDividers } ) }
				/>

				{ enableDividers && (
					<BaseControl
						label={ __( 'Divider Color', 'author-profile-blocks' ) }
					>
						<ColorPalette
							value={ dividerColor }
							onChange={ ( value ) =>
								setAttributes( { dividerColor: value } )
							}
						/>
					</BaseControl>
				) }

				<ToggleControl
					label={ __( 'Rounded Corners', 'author-profile-blocks' ) }
					checked={ enableRounded }
					onChange={ () => setAttributes( { enableRounded: ! enableRounded } ) }
				/>

				<ToggleControl
					label={ __( 'Hover Effects', 'author-profile-blocks' ) }
					checked={ enableHoverEffect }
					onChange={ () => setAttributes( { enableHoverEffect: ! enableHoverEffect } ) }
				/>

				{ enableHoverEffect && (
					<SelectControl
						label={ __( 'Hover Effect Type', 'author-profile-blocks' ) }
						value={ hoverEffect }
						options={ [
							{ value: 'none', label: __( 'None', 'author-profile-blocks' ) },
							{ value: 'lift', label: __( 'Lift Up', 'author-profile-blocks' ) },
							{ value: 'glow', label: __( 'Glow', 'author-profile-blocks' ) },
							{ value: 'scale', label: __( 'Scale', 'author-profile-blocks' ) },
							{ value: 'shadow', label: __( 'Shadow', 'author-profile-blocks' ) },
						] }
						onChange={ ( value ) => setAttributes( { hoverEffect: value } ) }
					/>
				) }

				<RangeControl
					label={ __( 'Item Padding', 'author-profile-blocks' ) }
					value={ itemPadding }
					onChange={ ( value ) => setAttributes( { itemPadding: value } ) }
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
			</PanelBody>

			<PanelBody
				title={ __( 'Border & Shadow', 'author-profile-blocks' ) }
				initialOpen={ false }
			>
				<RangeControl
					label={ __( 'Border Width', 'author-profile-blocks' ) }
					value={ borderWidth }
					onChange={ ( value ) => setAttributes( { borderWidth: value } ) }
					min={ 0 }
					max={ 10 }
				/>

				{ borderWidth > 0 && (
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
				) }

				<RangeControl
					label={ __( 'Border Radius', 'author-profile-blocks' ) }
					value={ borderRadius }
					onChange={ ( value ) => setAttributes( { borderRadius: value } ) }
					min={ 0 }
					max={ 50 }
				/>

				<ToggleControl
					label={ __( 'Box Shadow', 'author-profile-blocks' ) }
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