import type { ListInspectorProps } from '../../types';
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	TextControl,
	RangeControl,
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';
import {
	AlignJustify,
	Layers,
	Minus,
	Square,
	Layers2,
	Rows3,
} from 'lucide-react';
import DisplayStyleSelector from '../DisplayStyleSelector';
import ListLayoutSelector from '../ListLayoutSelector';

/**
 * Internal dependencies
 */
import {
	LayoutPresets,
	type LayoutOption,
} from '../../../../supports/js/components/inspector';

const LIST_LAYOUTS: LayoutOption[] = [
	{
		id: 'default',
		label: __( 'Default', 'author-profile-blocks' ),
		icon: AlignJustify,
		description: __(
			'Standard list layout with clean styling',
			'author-profile-blocks',
		),
	},
	{
		id: 'is-style-card',
		label: __( 'Card', 'author-profile-blocks' ),
		icon: Layers,
		description: __(
			'Card-based list with individual item containers',
			'author-profile-blocks',
		),
	},
	{
		id: 'is-style-minimal',
		label: __( 'Minimal', 'author-profile-blocks' ),
		icon: Minus,
		description: __(
			'Clean, minimal list design',
			'author-profile-blocks',
		),
	},
	{
		id: 'is-style-bordered',
		label: __( 'Bordered', 'author-profile-blocks' ),
		icon: Square,
		description: __(
			'List with visible borders around items',
			'author-profile-blocks',
		),
	},
	{
		id: 'is-style-shadow',
		label: __( 'Shadow', 'author-profile-blocks' ),
		icon: Layers2,
		description: __(
			'List with shadow effects on items',
			'author-profile-blocks',
		),
	},
	{
		id: 'is-style-alternating',
		label: __( 'Alternating', 'author-profile-blocks' ),
		icon: Rows3,
		description: __(
			'Alternating background colors for list items',
			'author-profile-blocks',
		),
	},
];

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
				layouts={ LIST_LAYOUTS }
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
