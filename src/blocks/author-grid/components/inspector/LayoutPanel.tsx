/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	SelectControl,
	TextControl,
	RangeControl,
	__experimentalUnitControl as UnitControl,
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
const LayoutPanel = ( { attributes, setAttributes } ) => {
	const {
		layoutPreset,
		columns,
		textAlign,
		margin,
		sectionSpacing,
		containerWidth,
	} = attributes;

	return (
		<>
			<LayoutPresets
				selectedLayout={ layoutPreset }
				onChange={ ( value ) => setAttributes( { layoutPreset: value } ) }
			/>

			<PanelBody title={ __( 'Grid Layout', 'author-profile-blocks' ) }>
				<SelectControl
					label={ __( 'Layout Preset', 'author-profile-blocks' ) }
					value={ layoutPreset }
					options={ [
						{ value: 'default', label: __( 'Default', 'author-profile-blocks' ) },
						{ value: 'is-style-card', label: __( 'Card Grid', 'author-profile-blocks' ) },
						{ value: 'is-style-minimal', label: __( 'Minimal', 'author-profile-blocks' ) },
						{ value: 'is-style-bordered', label: __( 'Bordered', 'author-profile-blocks' ) },
						{ value: 'is-style-shadow', label: __( 'Shadow Grid', 'author-profile-blocks' ) },
						{ value: 'is-style-mosaic', label: __( 'Mosaic', 'author-profile-blocks' ) },
					] }
					onChange={ ( value ) => setAttributes( { layoutPreset: value } ) }
				/>

				<RangeControl
					label={ __( 'Columns', 'author-profile-blocks' ) }
					value={ columns }
					onChange={ ( value ) => setAttributes( { columns: value } ) }
					min={ 1 }
					max={ 4 }
				/>

				<SelectControl
					label={ __( 'Text Alignment', 'author-profile-blocks' ) }
					value={ textAlign }
					options={ [
						{ value: 'left', label: __( 'Left', 'author-profile-blocks' ) },
						{ value: 'center', label: __( 'Center', 'author-profile-blocks' ) },
						{ value: 'right', label: __( 'Right', 'author-profile-blocks' ) },
					] }
					onChange={ ( value ) => setAttributes( { textAlign: value } ) }
				/>
			</PanelBody>

			<PanelBody
				title={ __( 'Spacing', 'author-profile-blocks' ) }
				initialOpen={ false }
			>
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
				/>
			</PanelBody>

			<PanelBody
				title={ __( 'Container', 'author-profile-blocks' ) }
				initialOpen={ false }
			>
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
			</PanelBody>
		</>
	);
};

export default LayoutPanel;
