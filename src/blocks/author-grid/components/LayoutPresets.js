import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';
import { LayoutGrid, Layers, Minus, Square, Layers2, LayoutDashboard } from 'lucide-react';

/**
 * Layout presets component for the Author Grid block
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
			icon: LayoutGrid,
			description: __( 'Standard grid layout with clean styling', 'author-profile-blocks' ),
		},
		{
			id: 'is-style-card',
			label: __( 'Card Grid', 'author-profile-blocks' ),
			icon: Layers,
			description: __( 'Individual cards in grid formation', 'author-profile-blocks' ),
		},
		{
			id: 'is-style-minimal',
			label: __( 'Minimal', 'author-profile-blocks' ),
			icon: Minus,
			description: __( 'Clean, minimal grid design', 'author-profile-blocks' ),
		},
		{
			id: 'is-style-bordered',
			label: __( 'Bordered', 'author-profile-blocks' ),
			icon: Square,
			description: __( 'Grid with visible borders around items', 'author-profile-blocks' ),
		},
		{
			id: 'is-style-shadow',
			label: __( 'Shadow Grid', 'author-profile-blocks' ),
			icon: Layers2,
			description: __( 'Grid with shadow effects on items', 'author-profile-blocks' ),
		},
		{
			id: 'is-style-mosaic',
			label: __( 'Mosaic', 'author-profile-blocks' ),
			icon: LayoutDashboard,
			description: __( 'Mosaic-style grid with varied item sizes', 'author-profile-blocks' ),
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
