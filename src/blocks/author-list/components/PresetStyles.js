import { __ } from '@wordpress/i18n';
import { Button, PanelBody } from '@wordpress/components';
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
		<PanelBody
			title={ __( 'Style Presets', 'author-profile-blocks' ) }
			initialOpen={ false }
		>
			<p style={ { margin: '0 0 16px 0', fontSize: '13px', color: '#666' } }>
				{ __(
					'Choose from pre-designed style presets to quickly apply professional styling to your author list.',
					'author-profile-blocks',
				) }
			</p>

			<div style={ { display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(140px, 1fr))', gap: '12px' } }>
				{ presets.map( ( { id, name, description, icon: Icon, settings } ) => (
					<Button
						key={ id }
						variant="secondary"
						className="apbl-preset-button"
						onClick={ () => onApplyPreset( settings ) }
						style={ {
							display: 'flex',
							flexDirection: 'column',
							alignItems: 'center',
							padding: '16px 12px',
							textAlign: 'center',
							minHeight: '100px',
							border: '1px solid #ddd',
							borderRadius: '6px',
							backgroundColor: '#fff',
							transition: 'all 0.2s ease',
						} }
						title={ description }
					>
						<div style={ { marginBottom: '8px', lineHeight: 1, color: '#4f46e5' } }>
							<Icon size={ 22 } strokeWidth={ 1.75 } />
						</div>
						<div style={ { fontSize: '12px', fontWeight: '600', color: '#333', lineHeight: '1.2' } }>
							{ name }
						</div>
					</Button>
				) ) }
			</div>

			<div style={ { marginTop: '16px', padding: '12px', backgroundColor: '#f0f7fc', borderRadius: '4px', border: '1px solid #c3e6fb' } }>
				<p style={ { margin: '0', fontSize: '12px', color: '#0066cc' } }>
					<strong>{ __( 'Tip:', 'author-profile-blocks' ) }</strong>{ ' ' }
					{ __(
						'Presets will override your current styling. You can still customize individual settings after applying a preset.',
						'author-profile-blocks',
					) }
				</p>
			</div>
		</PanelBody>
	);
}
