import { __ } from '@wordpress/i18n';
import { ButtonGroup, Button } from '@wordpress/components';
import { Layers, Columns, AlignCenter, Minus } from 'lucide-react';

interface Layout {
	name: string;
	label: string;
	icon: React.ComponentType< { size?: number; strokeWidth?: number } >;
}

const LAYOUTS: Layout[] = [
	{ name: 'card',     label: __( 'Card',     'author-profile-blocks' ), icon: Layers      },
	{ name: 'compact',  label: __( 'Compact',  'author-profile-blocks' ), icon: Columns     },
	{ name: 'centered', label: __( 'Centered', 'author-profile-blocks' ), icon: AlignCenter },
	{ name: 'minimal',  label: __( 'Minimal',  'author-profile-blocks' ), icon: Minus       },
];

interface Props {
	selectedLayout: string;
	onSelectLayout: ( layout: string ) => void;
}

const CarouselLayoutSelector = ( { selectedLayout, onSelectLayout }: Props ) => (
	<div className="apbl-layout-options">
		<ButtonGroup>
			{ LAYOUTS.map( ( { name, label, icon: Icon } ) => (
				<Button
					key={ name }
					className={ `apbl-layout-option ${ selectedLayout === name ? 'is-selected' : '' }` }
					isPressed={ selectedLayout === name }
					onClick={ () => onSelectLayout( name ) }
					label={ label }
					showTooltip
				>
					<Icon size={ 16 } strokeWidth={ 1.75 } />
					<span>{ label }</span>
				</Button>
			) ) }
		</ButtonGroup>
	</div>
);

export default CarouselLayoutSelector;
