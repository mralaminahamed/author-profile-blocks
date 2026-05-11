/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PanelBody } from '@wordpress/components';
import type { ComponentType } from 'react';

export interface StylePreset {
	id: string;
	name: string;
	description: string;
	icon: ComponentType< {
		size?: number;
		strokeWidth?: number;
		className?: string;
	} >;
	/**
	 * Attribute overrides applied when the user picks this preset.
	 * Shape is block-specific — the shared component does not interpret it.
	 */
	settings: Record< string, unknown >;
}

interface PresetStylesProps {
	presets: StylePreset[];
	onApplyPreset: ( settings: Record< string, unknown > ) => void;
	/** Optional panel title override. */
	title?: string;
}

/**
 * Shared style presets component.
 *
 * Renders a grid of preset cards inside a collapsible panel. Each consuming
 * block supplies its own `presets` list; render markup is identical across
 * blocks so the existing `.apbl-presets-grid` / `.apbl-preset-item` SCSS
 * continues to apply. The `settings` object on each preset is passed through
 * to `onApplyPreset` unchanged — block-specific attribute schemas live in
 * the consumer, not here.
 *
 * @param {PresetStylesProps} props               Component props
 * @param {StylePreset[]}     props.presets       Preset options to render
 * @param {Function}          props.onApplyPreset Callback when a preset is picked
 * @param {string}            [props.title]       Optional panel title override
 * @return {JSX.Element} Preset styles component
 */
export function PresetStyles( {
	presets,
	onApplyPreset,
	title,
}: PresetStylesProps ) {
	return (
		<PanelBody
			title={ title ?? __( 'Style Presets', 'author-profile-blocks' ) }
			initialOpen={ false }
		>
			<div className="apbl-presets-grid">
				{ presets.map( ( { id, name, description, icon: Icon, settings } ) => (
					<button
						key={ id }
						type="button"
						className="apbl-preset-item"
						onClick={ () => onApplyPreset( settings ) }
						title={ description }
					>
						<span className="apbl-preset-item__icon">
							<Icon size={ 20 } strokeWidth={ 1.75 } />
						</span>
						<span className="apbl-preset-item__label">{ name }</span>
					</button>
				) ) }
			</div>

			<p className="apbl-presets-tip">
				{ __(
					'Presets override current styles. You can still customize after applying.',
					'author-profile-blocks',
				) }
			</p>
		</PanelBody>
	);
}
