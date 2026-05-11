import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';
import { UserRound, IdCard, Minus, Frame, Layers2 } from 'lucide-react';

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
			icon: UserRound,
			description: __( 'Classic card layout with image and content', 'author-profile-blocks' ),
		},
		{
			id: 'is-style-card',
			label: __( 'Card', 'author-profile-blocks' ),
			icon: IdCard,
			description: __( 'Traditional card layout with clean styling', 'author-profile-blocks' ),
		},
		{
			id: 'is-style-minimal',
			label: __( 'Minimal', 'author-profile-blocks' ),
			icon: Minus,
			description: __( 'Clean, minimal design with essential information', 'author-profile-blocks' ),
		},
		{
			id: 'is-style-bordered',
			label: __( 'Bordered', 'author-profile-blocks' ),
			icon: Frame,
			description: __( 'Layout with visible borders', 'author-profile-blocks' ),
		},
		{
			id: 'is-style-shadow',
			label: __( 'Shadow', 'author-profile-blocks' ),
			icon: Layers2,
			description: __( 'Layout with shadow effects', 'author-profile-blocks' ),
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
