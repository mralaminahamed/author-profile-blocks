import type { GridInspectorProps } from '../../types';
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	SelectControl,
	TextControl,
	RangeControl,
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';
import { LayoutPresets } from '../LayoutPresets';
import GridLayoutSelector from '../GridLayoutSelector';

/**
 * LayoutPanel component for layout-related settings in the InspectorControls
 *
 * @param {Object}   props               Component props
 * @param {Object}   props.attributes    Block attributes
 * @param {Function} props.setAttributes Function to set block attributes
 * @return {JSX.Element} Layout panel component
 */
const LayoutPanel = ( { attributes, setAttributes }: GridInspectorProps ) => {
	const {
		layout,
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
				<GridLayoutSelector
					selectedLayout={ layout }
					onSelectLayout={ ( value ) => setAttributes( { layout: value } ) }
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
