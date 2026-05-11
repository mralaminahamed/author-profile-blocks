import { __ } from '@wordpress/i18n';
import { PanelBody } from '@wordpress/components';
import { Contact, Sparkles, Moon, Palette, Quote, Briefcase } from 'lucide-react';

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
			description: __( 'Traditional card layout with clean styling', 'author-profile-blocks' ),
			icon: Contact,
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
			description: __( 'Clean, minimal design with subtle shadows', 'author-profile-blocks' ),
			icon: Sparkles,
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
			description: __( 'Dark background with light text', 'author-profile-blocks' ),
			icon: Moon,
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
			description: __( 'Beautiful gradient background with modern styling', 'author-profile-blocks' ),
			icon: Palette,
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
			description: __( 'Quote-style layout perfect for testimonials', 'author-profile-blocks' ),
			icon: Quote,
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
			description: __( 'Professional business card layout', 'author-profile-blocks' ),
			icon: Briefcase,
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
