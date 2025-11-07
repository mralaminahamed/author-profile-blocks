/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button, PanelBody } from '@wordpress/components';

/**
 * Preset styles component for the Author Profile block
 *
 * @param {Object}   props               Component props
 * @param {Function} props.onApplyPreset Callback when a preset is applied
 * @return {JSX.Element} Preset styles component
 */
export function PresetStyles( { onApplyPreset } ) {
	const presets = [
		{
			id: 'classic',
			name: __( 'Classic Card', 'author-profile-blocks' ),
			description: __( 'Traditional card layout with clean styling' ),
			icon: '📄',
			settings: {
				backgroundColor: '#ffffff',
				borderWidth: 1,
				borderColor: '#e0e0e0',
				borderRadius: 8,
				padding: 20,
				boxShadow: true,
				boxShadowColor: 'rgba(0,0,0,0.1)',
				boxShadowBlur: 8,
				nameColor: '#333333',
				nameSize: 20,
				nameWeight: '600',
				descriptionColor: '#666666',
				metaColor: '#888888',
			},
		},
		{
			id: 'modern',
			name: __( 'Modern Minimal', 'author-profile-blocks' ),
			description: __( 'Clean, minimal design with subtle shadows' ),
			icon: '✨',
			settings: {
				backgroundColor: '#ffffff',
				borderWidth: 0,
				borderRadius: 12,
				padding: 24,
				boxShadow: true,
				boxShadowColor: 'rgba(0,0,0,0.08)',
				boxShadowBlur: 16,
				boxShadowVertical: 4,
				nameColor: '#1a1a1a',
				nameSize: 22,
				nameWeight: '500',
				descriptionColor: '#666666',
				metaColor: '#999999',
				avatarShape: 'circle',
				avatarBorderWidth: 2,
				avatarBorderColor: '#e0e0e0',
			},
		},
		{
			id: 'dark',
			name: __( 'Dark Theme', 'author-profile-blocks' ),
			description: __( 'Dark background with light text' ),
			icon: '🌙',
			settings: {
				backgroundColor: '#1a1a1a',
				borderWidth: 0,
				borderRadius: 8,
				padding: 20,
				boxShadow: true,
				boxShadowColor: 'rgba(0,0,0,0.3)',
				boxShadowBlur: 12,
				nameColor: '#ffffff',
				nameSize: 20,
				nameWeight: '600',
				descriptionColor: '#cccccc',
				metaColor: '#aaaaaa',
				emailLinkColor: '#4a9eff',
			},
		},
		{
			id: 'gradient',
			name: __( 'Gradient Style', 'author-profile-blocks' ),
			description: __( 'Beautiful gradient background with modern styling' ),
			icon: '🌈',
			settings: {
				gradientBackground: true,
				gradientStartColor: '#667eea',
				gradientEndColor: '#764ba2',
				gradientDirection: '135deg',
				borderWidth: 0,
				borderRadius: 16,
				padding: 24,
				nameColor: '#ffffff',
				nameSize: 24,
				nameWeight: '600',
				descriptionColor: '#f0f0f0',
				metaColor: '#e0e0e0',
				avatarBorderWidth: 3,
				avatarBorderColor: '#ffffff',
				boxShadow: true,
				boxShadowColor: 'rgba(0,0,0,0.2)',
				boxShadowBlur: 20,
			},
		},
		{
			id: 'testimonial',
			name: __( 'Testimonial', 'author-profile-blocks' ),
			description: __( 'Quote-style layout perfect for testimonials' ),
			icon: '💬',
			settings: {
				backgroundColor: '#f8f9fa',
				borderWidth: 0,
				borderRadius: 12,
				padding: 24,
				boxShadow: true,
				boxShadowColor: 'rgba(0,0,0,0.1)',
				boxShadowBlur: 12,
				nameColor: '#333333',
				nameSize: 18,
				nameWeight: '600',
				descriptionColor: '#555555',
				descriptionSize: 16,
				descriptionStyle: 'italic',
				metaColor: '#777777',
				avatarSize: 60,
				avatarShape: 'circle',
			},
		},
		{
			id: 'business',
			name: __( 'Business Card', 'author-profile-blocks' ),
			description: __( 'Professional business card layout' ),
			icon: '💼',
			settings: {
				backgroundColor: '#ffffff',
				borderWidth: 1,
				borderColor: '#cccccc',
				borderRadius: 4,
				padding: 16,
				boxShadow: false,
				nameColor: '#000000',
				nameSize: 16,
				nameWeight: '600',
				descriptionColor: '#333333',
				metaColor: '#666666',
				avatarSize: 50,
				avatarShape: 'square',
				showEmail: true,
				showDescription: false,
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
					'Choose from pre-designed style presets to quickly apply professional styling to your author profile.',
					'author-profile-blocks',
				) }
			</p>

			<div
				style={ {
					display: 'grid',
					gridTemplateColumns: 'repeat(auto-fit, minmax(140px, 1fr))',
					gap: '12px',
				} }
			>
				{ presets.map( ( preset ) => (
					<Button
						key={ preset.id }
						variant="secondary"
						className="apb-preset-button"
						onClick={ () => onApplyPreset( preset.settings ) }
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
						title={ preset.description }
					>
						<div
							style={ {
								fontSize: '24px',
								marginBottom: '8px',
								lineHeight: '1',
							} }
						>
							{ preset.icon }
						</div>
						<div
							style={ {
								fontSize: '12px',
								fontWeight: '600',
								color: '#333',
								lineHeight: '1.2',
							} }
						>
							{ preset.name }
						</div>
					</Button>
				) ) }
			</div>

			<div
				style={ {
					marginTop: '16px',
					padding: '12px',
					backgroundColor: '#f0f7fc',
					borderRadius: '4px',
					border: '1px solid #c3e6fb',
				} }
			>
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
