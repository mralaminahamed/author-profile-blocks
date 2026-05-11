import type { ListInspectorProps } from '../../types';
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	TextControl,
	RangeControl,
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';
import DisplayStyleSelector from '../DisplayStyleSelector';
import ListLayoutSelector from '../ListLayoutSelector';

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
const LayoutPanel = ( { attributes, setAttributes }: ListInspectorProps ) => {
	const {
		layoutPreset,
		displayStyle,
		listStyle,
		textAlign,
		blockPadding,
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

			<PanelBody title={ __( 'List Layout', 'author-profile-blocks' ) }>
				<DisplayStyleSelector
					value={ displayStyle }
					onChange={ ( value ) => setAttributes( { displayStyle: value } ) }
				/>

				<ListLayoutSelector
					value={ listStyle }
					onChange={ ( value ) => setAttributes( { listStyle: value } ) }
				/>
			</PanelBody>

			<PanelBody
				title={ __( 'Spacing', 'author-profile-blocks' ) }
				initialOpen={ false }
			>
				<RangeControl
					label={ __( 'Block Padding', 'author-profile-blocks' ) }
					value={ blockPadding }
					onChange={ ( value ) => setAttributes( { blockPadding: value } ) }
					min={ 0 }
					max={ 50 }
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
