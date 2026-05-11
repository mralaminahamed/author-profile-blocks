import { __ } from '@wordpress/i18n';
import { ButtonGroup, Button } from '@wordpress/components';
import { Layers, Columns, AlignCenter } from 'lucide-react';

/**
 * Grid layout selector component
 *
 * @param {Object}   props                Component props
 * @param {string}   props.selectedLayout Current selected layout
 * @param {Function} props.onSelectLayout Callback for layout selection
 * @return {JSX.Element} Component to render
 */
const GridLayoutSelector = ( { selectedLayout, onSelectLayout } ) => {
	const layouts = [
		{
			name: 'card',
			label: __( 'Card', 'author-profile-blocks' ),
			icon: Layers,
		},
		{
			name: 'compact',
			label: __( 'Compact', 'author-profile-blocks' ),
			icon: Columns,
		},
		{
			name: 'centered',
			label: __( 'Centered', 'author-profile-blocks' ),
			icon: AlignCenter,
		},
	];

	return (
		<div className="apbl-grid-layout-options">
			<ButtonGroup>
				{ layouts.map( ( { name, label, icon: Icon } ) => (
					<Button
						key={ name }
						className={ `apbl-layout-option ${ selectedLayout === name ? 'is-selected' : '' }` }
						isPressed={ selectedLayout === name }
						onClick={ () => onSelectLayout( name ) }
					>
						<Icon size={ 16 } strokeWidth={ 1.75 } />
						<span>{ label }</span>
					</Button>
				) ) }
			</ButtonGroup>
		</div>
	);
};

export default GridLayoutSelector;
