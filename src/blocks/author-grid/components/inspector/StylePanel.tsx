import type { GridInspectorProps } from '../../types';
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
import { LayoutGrid, Layers, Sparkles, Moon, Palette, Minus } from 'lucide-react';

/**
 * Internal dependencies
 */
import {
	AdvancedStyling,
	PresetStyles,
	type StylePreset,
} from '../../../../supports/js/components/inspector';

const GRID_PRESETS: StylePreset[] = [
	{
		id: 'clean-grid',
		name: __( 'Clean Grid', 'author-profile-blocks' ),
		description: __( 'Simple, clean grid with minimal styling', 'author-profile-blocks' ),
		icon: LayoutGrid,
		settings: {
			backgroundColor: '#ffffff',
			enableShadow: false,
			enableBorder: false,
			enableRounded: false,
			padding: 16,
			itemSpacing: 16,
			boxShadow: false,
		},
	},
	{
		id: 'card-grid',
		name: __( 'Card Grid', 'author-profile-blocks' ),
		description: __( 'Individual cards in grid formation', 'author-profile-blocks' ),
		icon: Layers,
		settings: {
			backgroundColor: '#ffffff',
			enableShadow: true,
			enableBorder: true,
			enableRounded: true,
			padding: 20,
			itemSpacing: 20,
			boxShadow: true,
			boxShadowColor: 'rgba(0,0,0,0.1)',
			boxShadowBlur: 8,
			boxShadowVertical: 4,
		},
	},
	{
		id: 'modern-grid',
		name: __( 'Modern Grid', 'author-profile-blocks' ),
		description: __( 'Contemporary grid with shadows and rounded corners', 'author-profile-blocks' ),
		icon: Sparkles,
		settings: {
			backgroundColor: '#f8f9fa',
			enableShadow: true,
			enableBorder: false,
			enableRounded: true,
			padding: 24,
			itemSpacing: 24,
			boxShadow: true,
			boxShadowColor: 'rgba(0,0,0,0.08)',
			boxShadowBlur: 16,
			boxShadowVertical: 4,
		},
	},
	{
		id: 'dark-grid',
		name: __( 'Dark Theme', 'author-profile-blocks' ),
		description: __( 'Dark background with light text', 'author-profile-blocks' ),
		icon: Moon,
		settings: {
			backgroundColor: '#1a1a1a',
			enableShadow: false,
			enableBorder: false,
			enableRounded: true,
			padding: 20,
			itemSpacing: 20,
			boxShadow: false,
		},
	},
	{
		id: 'gradient-grid',
		name: __( 'Gradient Style', 'author-profile-blocks' ),
		description: __( 'Beautiful gradient backgrounds', 'author-profile-blocks' ),
		icon: Palette,
		settings: {
			gradientBackground: true,
			gradientStartColor: '#667eea',
			gradientEndColor: '#764ba2',
			gradientDirection: 'to right',
			enableShadow: true,
			enableBorder: false,
			enableRounded: true,
			padding: 24,
			itemSpacing: 24,
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
			enableShadow: false,
			enableBorder: false,
			enableRounded: false,
			padding: 12,
			itemSpacing: 12,
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
const StylePanel = ( { attributes, setAttributes }: GridInspectorProps ) => {
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
				presets={ GRID_PRESETS }
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
