import { __ } from '@wordpress/i18n';
import { PanelBody } from '@wordpress/components';
import { List, Layers, Sparkles, Moon, Palette, Minus } from 'lucide-react';

/**
 * Preset styles component for the Author List block
 *
 * @param {Object}   props               Component props
 * @param {Function} props.onApplyPreset Callback when a preset is applied
 * @return {JSX.Element} Preset styles component
 */
export function PresetStyles( { onApplyPreset } ) {
	const presets = [
		{
			id: 'clean-list',
			name: __( 'Clean List', 'author-profile-blocks' ),
			description: __( 'Simple, clean list with minimal styling', 'author-profile-blocks' ),
			icon: List,
			settings: {
				backgroundColor: '#ffffff',
				itemBackgroundColor: '#ffffff',
				borderWidth: 0,
				borderRadius: 0,
				enableDividers: true,
				dividerColor: '#f0f0f0',
				enableRounded: false,
				enableHoverEffect: false,
				itemPadding: 12,
				itemSpacing: 12,
				boxShadow: false,
			},
		},
		{
			id: 'card-list',
			name: __( 'Card List', 'author-profile-blocks' ),
			description: __( 'Individual cards for each list item', 'author-profile-blocks' ),
			icon: Layers,
			settings: {
				backgroundColor: '#ffffff',
				itemBackgroundColor: '#ffffff',
				borderWidth: 1,
				borderColor: '#e0e0e0',
				borderRadius: 8,
				enableDividers: false,
				enableRounded: true,
				enableHoverEffect: true,
				hoverEffect: 'lift',
				itemPadding: 16,
				itemSpacing: 16,
				boxShadow: true,
				boxShadowColor: 'rgba(0,0,0,0.1)',
				boxShadowBlur: 8,
				boxShadowVertical: 2,
			},
		},
		{
			id: 'modern-grid',
			name: __( 'Modern Grid', 'author-profile-blocks' ),
			description: __( 'Contemporary grid layout with shadows', 'author-profile-blocks' ),
			icon: Sparkles,
			settings: {
				backgroundColor: '#f8f9fa',
				itemBackgroundColor: '#ffffff',
				borderWidth: 0,
				borderRadius: 12,
				enableDividers: false,
				enableRounded: true,
				enableHoverEffect: true,
				hoverEffect: 'glow',
				itemPadding: 20,
				itemSpacing: 20,
				boxShadow: true,
				boxShadowColor: 'rgba(0,0,0,0.08)',
				boxShadowBlur: 16,
				boxShadowVertical: 4,
			},
		},
		{
			id: 'dark-list',
			name: __( 'Dark Theme', 'author-profile-blocks' ),
			description: __( 'Dark background with light text', 'author-profile-blocks' ),
			icon: Moon,
			settings: {
				backgroundColor: '#1a1a1a',
				itemBackgroundColor: '#2a2a2a',
				borderWidth: 0,
				borderRadius: 8,
				enableDividers: true,
				dividerColor: '#404040',
				enableRounded: false,
				enableHoverEffect: true,
				hoverEffect: 'shadow',
				itemPadding: 16,
				itemSpacing: 16,
				boxShadow: false,
			},
		},
		{
			id: 'gradient-list',
			name: __( 'Gradient Style', 'author-profile-blocks' ),
			description: __( 'Beautiful gradient backgrounds', 'author-profile-blocks' ),
			icon: Palette,
			settings: {
				gradientBackground: true,
				gradientStartColor: '#667eea',
				gradientEndColor: '#764ba2',
				gradientDirection: 'to right',
				itemBackgroundColor: 'rgba(255,255,255,0.1)',
				borderWidth: 0,
				borderRadius: 12,
				enableDividers: false,
				enableRounded: true,
				enableHoverEffect: true,
				hoverEffect: 'scale',
				itemPadding: 18,
				itemSpacing: 18,
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
				itemBackgroundColor: 'transparent',
				borderWidth: 0,
				borderRadius: 0,
				enableDividers: false,
				enableRounded: false,
				enableHoverEffect: false,
				itemPadding: 8,
				itemSpacing: 8,
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
