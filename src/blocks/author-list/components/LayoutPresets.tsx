/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';

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
			icon: '📋',
			description: __( 'Standard list layout with clean styling' ),
		},
		{
			id: 'is-style-card',
			label: __( 'Card', 'author-profile-blocks' ),
			icon: '🃏',
			description: __( 'Card-based list with individual item containers' ),
		},
		{
			id: 'is-style-minimal',
			label: __( 'Minimal', 'author-profile-blocks' ),
			icon: '⚪',
			description: __( 'Clean, minimal list design' ),
		},
		{
			id: 'is-style-bordered',
			label: __( 'Bordered', 'author-profile-blocks' ),
			icon: '🔲',
			description: __( 'List with visible borders around items' ),
		},
		{
			id: 'is-style-shadow',
			label: __( 'Shadow', 'author-profile-blocks' ),
			icon: '🌑',
			description: __( 'List with shadow effects on items' ),
		},
		{
			id: 'is-style-alternating',
			label: __( 'Alternating', 'author-profile-blocks' ),
			icon: '🔄',
			description: __( 'Alternating background colors for list items' ),
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
