/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';

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
			icon: '🔳',
			description: __( 'Standard grid layout with clean styling' ),
		},
		{
			id: 'is-style-card',
			label: __( 'Card Grid', 'author-profile-blocks' ),
			icon: '🃏',
			description: __( 'Individual cards in grid formation' ),
		},
		{
			id: 'is-style-minimal',
			label: __( 'Minimal', 'author-profile-blocks' ),
			icon: '⚪',
			description: __( 'Clean, minimal grid design' ),
		},
		{
			id: 'is-style-bordered',
			label: __( 'Bordered', 'author-profile-blocks' ),
			icon: '🔲',
			description: __( 'Grid with visible borders around items' ),
		},
		{
			id: 'is-style-shadow',
			label: __( 'Shadow Grid', 'author-profile-blocks' ),
			icon: '🌑',
			description: __( 'Grid with shadow effects on items' ),
		},
		{
			id: 'is-style-mosaic',
			label: __( 'Mosaic', 'author-profile-blocks' ),
			icon: '🧩',
			description: __( 'Mosaic-style grid with varied item sizes' ),
		},
	];

	return (
		<div className="apb-layout-presets">
			<div className="apb-layout-grid">
				{ layouts.map( ( layout ) => (
					<Button
						key={ layout.id }
						variant={ selectedLayout === layout.id ? 'primary' : 'secondary' }
						className={ `apb-layout-preset ${
							selectedLayout === layout.id ? 'is-selected' : ''
						}` }
						onClick={ () => onChange( layout.id ) }
						title={ layout.description }
					>
						<div className="apb-layout-icon">{ layout.icon }</div>
						<div className="apb-layout-label">{ layout.label }</div>
					</Button>
				) ) }
			</div>
		</div>
	);
}
