import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';
import { AlignJustify, Layers, Minus, Square, Layers2, Rows3 } from 'lucide-react';

/**
 * Layout presets component for the Author List block
 *
 * @param {Object}   props                Component props
 * @param {string}   props.selectedLayout Currently selected layout
 * @param {Function} props.onChange       Callback when layout changes
 * @return {JSX.Element} Layout presets component
 */
export function LayoutPresets( { selectedLayout, onChange } ) {
	const layouts = [
		{
			id: 'default',
			label: __( 'Default', 'author-profile-blocks' ),
			icon: AlignJustify,
			description: __( 'Standard list layout with clean styling', 'author-profile-blocks' ),
		},
		{
			id: 'is-style-card',
			label: __( 'Card', 'author-profile-blocks' ),
			icon: Layers,
			description: __( 'Card-based list with individual item containers', 'author-profile-blocks' ),
		},
		{
			id: 'is-style-minimal',
			label: __( 'Minimal', 'author-profile-blocks' ),
			icon: Minus,
			description: __( 'Clean, minimal list design', 'author-profile-blocks' ),
		},
		{
			id: 'is-style-bordered',
			label: __( 'Bordered', 'author-profile-blocks' ),
			icon: Square,
			description: __( 'List with visible borders around items', 'author-profile-blocks' ),
		},
		{
			id: 'is-style-shadow',
			label: __( 'Shadow', 'author-profile-blocks' ),
			icon: Layers2,
			description: __( 'List with shadow effects on items', 'author-profile-blocks' ),
		},
		{
			id: 'is-style-alternating',
			label: __( 'Alternating', 'author-profile-blocks' ),
			icon: Rows3,
			description: __( 'Alternating background colors for list items', 'author-profile-blocks' ),
		},
	];

	return (
		<div className="apbl-layout-presets">
			<div className="apbl-layout-grid">
				{ layouts.map( ( { id, label, description, icon: Icon } ) => (
					<Button
						key={ id }
						variant={ selectedLayout === id ? 'primary' : 'secondary' }
						className={ `apbl-layout-preset ${ selectedLayout === id ? 'is-selected' : '' }` }
						onClick={ () => onChange( id ) }
						title={ description }
					>
						<div className="apbl-layout-icon">
							<Icon size={ 18 } strokeWidth={ 1.75 } />
						</div>
						<div className="apbl-layout-label">{ label }</div>
					</Button>
				) ) }
			</div>
		</div>
	);
}
