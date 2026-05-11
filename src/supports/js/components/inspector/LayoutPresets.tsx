/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';
import type { ComponentType } from 'react';

export interface LayoutOption {
	id: string;
	label: string;
	icon: ComponentType< {
		size?: number;
		strokeWidth?: number;
		className?: string;
	} >;
	description: string;
}

interface LayoutPresetsProps {
	layouts: LayoutOption[];
	selectedLayout: string;
	onChange: ( layoutId: string ) => void;
}

/**
 * Shared layout presets component.
 *
 * Renders a Button group of layout choices. Each consuming block supplies
 * its own `layouts` list; render markup is identical across blocks so the
 * existing `.apbl-layout-presets` SCSS continues to apply.
 *
 * @param {LayoutPresetsProps} props                Component props
 * @param {LayoutOption[]}     props.layouts        Layout options to render
 * @param {string}             props.selectedLayout Currently selected layout id
 * @param {Function}           props.onChange       Callback when layout changes
 * @return {JSX.Element} Layout presets component
 */
export function LayoutPresets( {
	layouts,
	selectedLayout,
	onChange,
}: LayoutPresetsProps ) {
	return (
		<div className="apbl-layout-presets">
			<div className="apbl-layout-grid">
				{ layouts.map( ( { id, label, description, icon: Icon } ) => (
					<Button
						key={ id }
						variant={
							selectedLayout === id ? 'primary' : 'secondary'
						}
						className={ `apbl-layout-preset ${
							selectedLayout === id ? 'is-selected' : ''
						}` }
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
