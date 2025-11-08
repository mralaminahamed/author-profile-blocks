/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PanelBody, SelectControl, RangeControl, TextControl } from '@wordpress/components';

/**
 * Layout Panel Component
 *
 * @param {Object}   props               Component props
 * @param {Object}   props.attributes    Block attributes
 * @param {Function} props.setAttributes Set attributes function
 * @return {JSX.Element} Layout panel
 */
export default function LayoutPanel( { attributes, setAttributes } ) {
	const {
		layout,
		textAlign,
		padding,
		slideSpacing,
		enableShadow,
		enableBorder,
		borderColor,
		borderWidth,
		enableRounded,
		layoutPreset,
		margin,
		sectionSpacing,
		borderRadius,
		containerWidth,
	} = attributes;

	return (
		<PanelBody title={ __( 'Layout', 'author-profile-blocks' ) }>
			<SelectControl
				label={ __( 'Layout Style', 'author-profile-blocks' ) }
				value={ layout }
				options={ [
					{ label: __( 'Card', 'author-profile-blocks' ), value: 'card' },
					{ label: __( 'Minimal', 'author-profile-blocks' ), value: 'minimal' },
					{ label: __( 'Bordered', 'author-profile-blocks' ), value: 'bordered' },
					{ label: __( 'Shadow', 'author-profile-blocks' ), value: 'shadow' },
				] }
				onChange={ ( value ) =>
					setAttributes( { layout: value } )
				}
			/>

			<SelectControl
				label={ __( 'Layout Preset', 'author-profile-blocks' ) }
				value={ layoutPreset }
				options={ [
					{ label: __( 'Default', 'author-profile-blocks' ), value: 'default' },
					{ label: __( 'Modern Cards', 'author-profile-blocks' ), value: 'modern-cards' },
					{ label: __( 'Classic Carousel', 'author-profile-blocks' ), value: 'classic-carousel' },
					{ label: __( 'Minimal Slides', 'author-profile-blocks' ), value: 'minimal-slides' },
					{ label: __( 'Elegant Profile', 'author-profile-blocks' ), value: 'elegant-profile' },
					{ label: __( 'Creative Layout', 'author-profile-blocks' ), value: 'creative-layout' },
				] }
				onChange={ ( value ) =>
					setAttributes( { layoutPreset: value } )
				}
			/>

			<SelectControl
				label={ __( 'Text Alignment', 'author-profile-blocks' ) }
				value={ textAlign }
				options={ [
					{ label: __( 'Left', 'author-profile-blocks' ), value: 'left' },
					{ label: __( 'Center', 'author-profile-blocks' ), value: 'center' },
					{ label: __( 'Right', 'author-profile-blocks' ), value: 'right' },
				] }
				onChange={ ( value ) =>
					setAttributes( { textAlign: value } )
				}
			/>

			<RangeControl
				label={ __( 'Padding', 'author-profile-blocks' ) }
				value={ padding }
				onChange={ ( value ) =>
					setAttributes( { padding: value } )
				}
				min={ 0 }
				max={ 100 }
			/>

			<RangeControl
				label={ __( 'Slide Spacing', 'author-profile-blocks' ) }
				value={ slideSpacing }
				onChange={ ( value ) =>
					setAttributes( { slideSpacing: value } )
				}
				min={ 0 }
				max={ 50 }
			/>

			<RangeControl
				label={ __( 'Section Spacing', 'author-profile-blocks' ) }
				value={ sectionSpacing }
				onChange={ ( value ) =>
					setAttributes( { sectionSpacing: value } )
				}
				min={ 0 }
				max={ 100 }
			/>

			<TextControl
				label={ __( 'Margin', 'author-profile-blocks' ) }
				value={ margin }
				onChange={ ( value ) =>
					setAttributes( { margin: value } )
				}
				placeholder={ __( 'e.g., 0 auto, 20px, etc.', 'author-profile-blocks' ) }
			/>

			<TextControl
				label={ __( 'Container Width', 'author-profile-blocks' ) }
				value={ containerWidth }
				onChange={ ( value ) =>
					setAttributes( { containerWidth: value } )
				}
				placeholder={ __( 'e.g., 100%, 1200px, etc.', 'author-profile-blocks' ) }
			/>

			<RangeControl
				label={ __( 'Border Radius', 'author-profile-blocks' ) }
				value={ borderRadius }
				onChange={ ( value ) =>
					setAttributes( { borderRadius: value } )
				}
				min={ 0 }
				max={ 50 }
			/>

			<RangeControl
				label={ __( 'Border Width', 'author-profile-blocks' ) }
				value={ borderWidth }
				onChange={ ( value ) =>
					setAttributes( { borderWidth: value } )
				}
				min={ 0 }
				max={ 10 }
			/>

			<TextControl
				label={ __( 'Border Color', 'author-profile-blocks' ) }
				value={ borderColor }
				onChange={ ( value ) =>
					setAttributes( { borderColor: value } )
				}
				type="color"
			/>
		</PanelBody>
	);
}
