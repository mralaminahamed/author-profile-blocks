import type { ListInspectorProps } from '../../types';
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
import { List, Layers, Sparkles, Moon, Palette, Minus } from 'lucide-react';

/**
 * Internal dependencies
 */
import {
	AdvancedStyling,
	PresetStyles,
	type StylePreset,
} from '../../../../supports/js/components/inspector';

const LIST_PRESETS: StylePreset[] = [
	{
		id: 'clean-list',
		name: __( 'Clean List', 'author-profile-blocks' ),
		description: __( 'Simple, clean list with minimal styling', 'author-profile-blocks' ),
		icon: List,
		settings: {
			backgroundColor: '#ffffff',
			itemBackgroundColor: '#ffffff',
			borderWidth: 0,
			borderRadius: 0,
			enableDividers: true,
			dividerColor: '#f0f0f0',
			enableRounded: false,
			enableHoverEffect: false,
			itemPadding: 12,
			itemSpacing: 12,
			boxShadow: false,
		},
	},
	{
		id: 'card-list',
		name: __( 'Card List', 'author-profile-blocks' ),
		description: __( 'Individual cards for each list item', 'author-profile-blocks' ),
		icon: Layers,
		settings: {
			backgroundColor: '#ffffff',
			itemBackgroundColor: '#ffffff',
			borderWidth: 1,
			borderColor: '#e0e0e0',
			borderRadius: 8,
			enableDividers: false,
			enableRounded: true,
			enableHoverEffect: true,
			hoverEffect: 'lift',
			itemPadding: 16,
			itemSpacing: 16,
			boxShadow: true,
			boxShadowColor: 'rgba(0,0,0,0.1)',
			boxShadowBlur: 8,
			boxShadowVertical: 2,
		},
	},
	{
		id: 'modern-grid',
		name: __( 'Modern Grid', 'author-profile-blocks' ),
		description: __( 'Contemporary grid layout with shadows', 'author-profile-blocks' ),
		icon: Sparkles,
		settings: {
			backgroundColor: '#f8f9fa',
			itemBackgroundColor: '#ffffff',
			borderWidth: 0,
			borderRadius: 12,
			enableDividers: false,
			enableRounded: true,
			enableHoverEffect: true,
			hoverEffect: 'glow',
			itemPadding: 20,
			itemSpacing: 20,
			boxShadow: true,
			boxShadowColor: 'rgba(0,0,0,0.08)',
			boxShadowBlur: 16,
			boxShadowVertical: 4,
		},
	},
	{
		id: 'dark-list',
		name: __( 'Dark Theme', 'author-profile-blocks' ),
		description: __( 'Dark background with light text', 'author-profile-blocks' ),
		icon: Moon,
		settings: {
			backgroundColor: '#1a1a1a',
			itemBackgroundColor: '#2a2a2a',
			borderWidth: 0,
			borderRadius: 8,
			enableDividers: true,
			dividerColor: '#404040',
			enableRounded: false,
			enableHoverEffect: true,
			hoverEffect: 'shadow',
			itemPadding: 16,
			itemSpacing: 16,
			boxShadow: false,
		},
	},
	{
		id: 'gradient-list',
		name: __( 'Gradient Style', 'author-profile-blocks' ),
		description: __( 'Beautiful gradient backgrounds', 'author-profile-blocks' ),
		icon: Palette,
		settings: {
			gradientBackground: true,
			gradientStartColor: '#667eea',
			gradientEndColor: '#764ba2',
			gradientDirection: 'to right',
			itemBackgroundColor: 'rgba(255,255,255,0.1)',
			borderWidth: 0,
			borderRadius: 12,
			enableDividers: false,
			enableRounded: true,
			enableHoverEffect: true,
			hoverEffect: 'scale',
			itemPadding: 18,
			itemSpacing: 18,
			boxShadow: true,
			boxShadowColor: 'rgba(0,0,0,0.15)',
			boxShadowBlur: 12,
		},
	},
	{
		id: 'minimalist',
		name: __( 'Minimalist', 'author-profile-blocks' ),
		description: __( 'Ultra-clean with no borders or shadows', 'author-profile-blocks' ),
		icon: Minus,
		settings: {
			backgroundColor: '#ffffff',
			itemBackgroundColor: 'transparent',
			borderWidth: 0,
			borderRadius: 0,
			enableDividers: false,
			enableRounded: false,
			enableHoverEffect: false,
			itemPadding: 8,
			itemSpacing: 8,
			boxShadow: false,
		},
	},
];

/**
 * StylePanel component for styling settings in the InspectorControls
 *
 * @param {Object}   props               Component props
 * @param {Object}   props.attributes    Block attributes
 * @param {Function} props.setAttributes Function to set block attributes
 * @return {JSX.Element} Style panel component
 */
const StylePanel = ( { attributes, setAttributes }: ListInspectorProps ) => {
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
				presets={ LIST_PRESETS }
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
