/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';

/**
 * Layout presets component for the Author Profile block
 *
 * @param {Object}   props                Component props
 * @param {string}   props.selectedLayout Currently selected layout
 * @param {Function} props.onChange       Callback when layout changes
 * @return {JSX.Element} Layout presets component
 */
export function LayoutPresets( { selectedLayout, onChange } ) {
	const layouts = [
		{
			id: '',
			label: __( 'Default', 'author-profile-blocks' ),
			icon: '👤',
			description: __( 'Classic card layout with image and content' ),
		},
		{
			id: 'is-style-card',
			label: __( 'Card', 'author-profile-blocks' ),
			icon: '📄',
			description: __( 'Traditional card layout with clean styling' ),
		},
		{
			id: 'is-style-minimal',
			label: __( 'Minimal', 'author-profile-blocks' ),
			icon: '⚪',
			description: __( 'Clean, minimal design with essential information' ),
		},
		{
			id: 'is-style-bordered',
			label: __( 'Bordered', 'author-profile-blocks' ),
			icon: '🔲',
			description: __( 'Layout with visible borders' ),
		},
		{
			id: 'is-style-shadow',
			label: __( 'Shadow', 'author-profile-blocks' ),
			icon: '🌑',
			description: __( 'Layout with shadow effects' ),
		},
		{
			id: 'is-style-banner',
			label: __( 'Banner', 'author-profile-blocks' ),
			icon: '📢',
			description: __( 'Wide banner layout perfect for headers' ),
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
