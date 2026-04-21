import { __ } from '@wordpress/i18n';
import { Button, PanelBody } from '@wordpress/components';
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
		<PanelBody
			title={ __( 'Style Presets', 'author-profile-blocks' ) }
			initialOpen={ false }
		>
			<p style={ { margin: '0 0 16px 0', fontSize: '13px', color: '#666' } }>
				{ __(
					'Choose from pre-designed style presets to quickly apply professional styling to your author grid.',
					'author-profile-blocks',
				) }
			</p>

			<div style={ { display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(140px, 1fr))', gap: '12px' } }>
				{ presets.map( ( { id, name, description, icon: Icon, settings } ) => (
					<Button
						key={ id }
						variant="secondary"
						className="apb-preset-button"
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
