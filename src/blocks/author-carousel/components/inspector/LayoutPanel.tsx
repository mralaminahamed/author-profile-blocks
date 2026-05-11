import type { CarouselInspectorProps } from '../../types';
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	SelectControl,
	RangeControl,
	TextControl,
	BaseControl,
	ColorPalette,
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';

const LAYOUT_OPTIONS = [
	{ label: __( 'Card', 'author-profile-blocks' ), value: 'card' },
	{ label: __( 'Minimal', 'author-profile-blocks' ), value: 'minimal' },
	{ label: __( 'Bordered', 'author-profile-blocks' ), value: 'bordered' },
	{ label: __( 'Shadow', 'author-profile-blocks' ), value: 'shadow' },
];

const PRESET_OPTIONS = [
	{ label: __( 'Default', 'author-profile-blocks' ), value: 'default' },
	{ label: __( 'Modern Cards', 'author-profile-blocks' ), value: 'is-style-modern-cards' },
	{ label: __( 'Classic Carousel', 'author-profile-blocks' ), value: 'is-style-classic-carousel' },
	{ label: __( 'Minimal Slides', 'author-profile-blocks' ), value: 'is-style-minimal' },
	{ label: __( 'Elegant Profile', 'author-profile-blocks' ), value: 'is-style-elegant-profile' },
	{ label: __( 'Creative Layout', 'author-profile-blocks' ), value: 'is-style-creative-layout' },
];

const ALIGN_OPTIONS = [
	{ label: __( 'Left', 'author-profile-blocks' ), value: 'left' },
	{ label: __( 'Center', 'author-profile-blocks' ), value: 'center' },
	{ label: __( 'Right', 'author-profile-blocks' ), value: 'right' },
];

export default function LayoutPanel( { attributes, setAttributes }: CarouselInspectorProps ) {
	const {
		layout,
		layoutPreset,
		textAlign,
		padding,
		slideSpacing,
		sectionSpacing,
		margin,
		containerWidth,
		borderRadius,
		borderWidth,
		borderColor,
		enableBorder,
		enableRounded,
	} = attributes;

	return (
		<>
			<PanelBody title={ __( 'Layout', 'author-profile-blocks' ) } initialOpen={ true }>
				<SelectControl
					label={ __( 'Item Style', 'author-profile-blocks' ) }
					value={ layout }
					options={ LAYOUT_OPTIONS }
					onChange={ ( value ) => setAttributes( { layout: value } ) }
				/>

				<SelectControl
					label={ __( 'Layout Preset', 'author-profile-blocks' ) }
					value={ layoutPreset }
					options={ PRESET_OPTIONS }
					onChange={ ( value ) => setAttributes( { layoutPreset: value } ) }
				/>

				<SelectControl
					label={ __( 'Text Alignment', 'author-profile-blocks' ) }
					value={ textAlign }
					options={ ALIGN_OPTIONS }
					onChange={ ( value ) => setAttributes( { textAlign: value } ) }
				/>
			</PanelBody>

			<PanelBody title={ __( 'Spacing', 'author-profile-blocks' ) } initialOpen={ false }>
				<RangeControl
					label={ __( 'Block Padding', 'author-profile-blocks' ) }
					value={ padding }
					onChange={ ( value ) => setAttributes( { padding: value } ) }
					min={ 0 }
					max={ 100 }
				/>

				<RangeControl
					label={ __( 'Slide Spacing', 'author-profile-blocks' ) }
					value={ slideSpacing }
					onChange={ ( value ) => setAttributes( { slideSpacing: value } ) }
					min={ 0 }
					max={ 50 }
				/>

				<RangeControl
					label={ __( 'Section Spacing', 'author-profile-blocks' ) }
					value={ sectionSpacing }
					onChange={ ( value ) => setAttributes( { sectionSpacing: value } ) }
					min={ 0 }
					max={ 100 }
				/>

				<UnitControl
					label={ __( 'Margin', 'author-profile-blocks' ) }
					value={ margin }
					onChange={ ( value ) => setAttributes( { margin: value } ) }
					units={ [
						{ value: 'px', label: 'px', default: 0 },
						{ value: 'em', label: 'em', default: 0 },
						{ value: '%', label: '%', default: 0 },
					] }
				/>
			</PanelBody>

			<PanelBody title={ __( 'Border', 'author-profile-blocks' ) } initialOpen={ false }>
				<RangeControl
					label={ __( 'Border Width', 'author-profile-blocks' ) }
					value={ borderWidth }
					onChange={ ( value ) => setAttributes( { borderWidth: value } ) }
					min={ 0 }
					max={ 10 }
				/>

				{ borderWidth > 0 && (
					<BaseControl label={ __( 'Border Color', 'author-profile-blocks' ) }>
						<ColorPalette
							value={ borderColor }
							onChange={ ( value ) => setAttributes( { borderColor: value } ) }
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
			</PanelBody>

			<PanelBody title={ __( 'Container', 'author-profile-blocks' ) } initialOpen={ false }>
				<TextControl
					label={ __( 'Max Width', 'author-profile-blocks' ) }
					value={ containerWidth }
					onChange={ ( value ) => setAttributes( { containerWidth: value } ) }
					placeholder={ __( 'e.g. 100%, 1200px', 'author-profile-blocks' ) }
				/>
			</PanelBody>
		</>
	);
}
