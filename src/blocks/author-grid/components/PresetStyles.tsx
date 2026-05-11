import { __ } from '@wordpress/i18n';
import { PanelBody } from '@wordpress/components';
import { LayoutGrid, Layers, Sparkles, Moon, Palette, Minus } from 'lucide-react';

/**
 * Preset styles component for the Author Grid block
 *
 * @param {Object}   props               Component props
 * @param {Function} props.onApplyPreset Callback when a preset is applied
 * @return {JSX.Element} Preset styles component
 */
export function PresetStyles( { onApplyPreset } ) {
	const presets = [
		{
			id: 'clean-grid',
			name: __( 'Clean Grid', 'author-profile-blocks' ),
			description: __( 'Simple, clean grid with minimal styling', 'author-profile-blocks' ),
			icon: LayoutGrid,
			settings: {
				backgroundColor: '#ffffff',
				enableShadow: false,
				enableBorder: false,
				enableRounded: false,
				padding: 16,
				itemSpacing: 16,
				boxShadow: false,
			},
		},
		{
			id: 'card-grid',
			name: __( 'Card Grid', 'author-profile-blocks' ),
			description: __( 'Individual cards in grid formation', 'author-profile-blocks' ),
			icon: Layers,
			settings: {
				backgroundColor: '#ffffff',
				enableShadow: true,
				enableBorder: true,
				enableRounded: true,
				padding: 20,
				itemSpacing: 20,
				boxShadow: true,
				boxShadowColor: 'rgba(0,0,0,0.1)',
				boxShadowBlur: 8,
				boxShadowVertical: 4,
			},
		},
		{
			id: 'modern-grid',
			name: __( 'Modern Grid', 'author-profile-blocks' ),
			description: __( 'Contemporary grid with shadows and rounded corners', 'author-profile-blocks' ),
			icon: Sparkles,
			settings: {
				backgroundColor: '#f8f9fa',
				enableShadow: true,
				enableBorder: false,
				enableRounded: true,
				padding: 24,
				itemSpacing: 24,
				boxShadow: true,
				boxShadowColor: 'rgba(0,0,0,0.08)',
				boxShadowBlur: 16,
				boxShadowVertical: 4,
			},
		},
		{
			id: 'dark-grid',
			name: __( 'Dark Theme', 'author-profile-blocks' ),
			description: __( 'Dark background with light text', 'author-profile-blocks' ),
			icon: Moon,
			settings: {
				backgroundColor: '#1a1a1a',
				enableShadow: false,
				enableBorder: false,
				enableRounded: true,
				padding: 20,
				itemSpacing: 20,
				boxShadow: false,
			},
		},
		{
			id: 'gradient-grid',
			name: __( 'Gradient Style', 'author-profile-blocks' ),
			description: __( 'Beautiful gradient backgrounds', 'author-profile-blocks' ),
			icon: Palette,
			settings: {
				gradientBackground: true,
				gradientStartColor: '#667eea',
				gradientEndColor: '#764ba2',
				gradientDirection: 'to right',
				enableShadow: true,
				enableBorder: false,
				enableRounded: true,
				padding: 24,
				itemSpacing: 24,
				boxShadow: true,
				boxShadowColor: 'rgba(0,0,0,0.15)',
				boxShadowBlur: 12,
			},
		},
		{
			id: 'minimalist',
			name: __( 'Minimalist', 'author-profile-blocks' ),
			description: __( 'Ultra-clean with no borders or shadows', 'author-profile-blocks' ),
			icon: Minus,
			settings: {
				backgroundColor: '#ffffff',
				enableShadow: false,
				enableBorder: false,
				enableRounded: false,
				padding: 12,
				itemSpacing: 12,
				boxShadow: false,
			},
		},
	];

	return (
		<PanelBody title={ __( 'Style Presets', 'author-profile-blocks' ) } initialOpen={ false }>
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
				{ __( 'Presets override current styles. You can still customize after applying.', 'author-profile-blocks' ) }
			</p>
		</PanelBody>
	);
}
