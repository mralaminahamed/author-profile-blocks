import type { ProfileInspectorProps } from '../../types';
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	RadioControl,
	SelectControl,
	TextControl,
	RangeControl,
	__experimentalUnitControl as UnitControl,
	BaseControl,
	ColorPalette,
	ToggleControl,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import { LayoutPresets } from '../LayoutPresets';

/**
 * LayoutPanel component for layout-related settings in the InspectorControls
 *
 * @param {Object}   props               Component props
 * @param {Object}   props.attributes    Block attributes
 * @param {Function} props.setAttributes Function to set block attributes
 * @return {JSX.Element} Layout panel component
 */
const LayoutPanel = ( { attributes, setAttributes }: ProfileInspectorProps ) => {
	const {
		blockStyle,
		contentOrder,
		containerWidth,
		textAlign,
		padding,
		margin,
		sectionSpacing,
		borderWidth,
		borderColor,
		borderRadius,
		boxShadow,
		boxShadowColor,
		boxShadowHorizontal,
		boxShadowVertical,
		boxShadowBlur,
		boxShadowSpread,
	} = attributes;

	return (
		<>
			<LayoutPresets
				selectedLayout={ blockStyle }
				onChange={ ( value ) => setAttributes( { blockStyle: value } ) }
			/>

			<PanelBody title={ __( 'Layout Options', 'author-profile-blocks' ) }>

				<RadioControl
					label={ __( 'Content Layout', 'author-profile-blocks' ) }
					selected={ contentOrder || 'image-content' }
					options={ [
						{
							label: __(
								'Image Left, Content Right',
								'author-profile-blocks',
							),
							value: 'image-content',
						},
						{
							label: __(
								'Image Right, Content Left',
								'author-profile-blocks',
							),
							value: 'content-image',
						},
						{
							label: __(
								'Image Top, Content Bottom',
								'author-profile-blocks',
							),
							value: 'image-top',
						},
						{
							label: __(
								'Content Top, Image Bottom',
								'author-profile-blocks',
							),
							value: 'content-top',
						},
					] }
					onChange={ ( value ) => setAttributes( { contentOrder: value } ) }
				/>

				<TextControl
					label={ __( 'Container Width', 'author-profile-blocks' ) }
					value={ containerWidth || '' }
					onChange={ ( value ) =>
						setAttributes( { containerWidth: value } )
					}
					help={ __(
						'Example: 100%, 450px, 50rem, etc.',
						'author-profile-blocks',
					) }
				/>

				<SelectControl
					label={ __( 'Text Alignment', 'author-profile-blocks' ) }
					value={ textAlign }
					options={ [
						{
							label: __( 'Left', 'author-profile-blocks' ),
							value: 'left',
						},
						{
							label: __( 'Center', 'author-profile-blocks' ),
							value: 'center',
						},
						{
							label: __( 'Right', 'author-profile-blocks' ),
							value: 'right',
						},
					] }
					onChange={ ( value ) => setAttributes( { textAlign: value } ) }
				/>
			</PanelBody>

			<PanelBody
				title={ __( 'Spacing', 'author-profile-blocks' ) }
				initialOpen={ false }
			>
				<RangeControl
					label={ __( 'Padding', 'author-profile-blocks' ) }
					value={ padding }
					onChange={ ( value ) => setAttributes( { padding: value } ) }
					min={ 0 }
					max={ 80 }
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

				<RangeControl
					label={ __( 'Section Spacing', 'author-profile-blocks' ) }
					value={ sectionSpacing }
					onChange={ ( value ) =>
						setAttributes( { sectionSpacing: value } )
					}
					min={ 0 }
					max={ 50 }
					initialPosition={ 15 }
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
					initialPosition={ 0 }
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
					initialPosition={ 0 }
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
							label={ __(
								'Horizontal Offset',
								'author-profile-blocks',
							) }
							value={ boxShadowHorizontal }
							onChange={ ( value ) =>
								setAttributes( { boxShadowHorizontal: value } )
							}
							min={ -50 }
							max={ 50 }
						/>

						<RangeControl
							label={ __(
								'Vertical Offset',
								'author-profile-blocks',
							) }
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

export default LayoutPanel;
