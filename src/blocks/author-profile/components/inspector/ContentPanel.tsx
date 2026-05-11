import type { ProfileInspectorProps } from '../../types';
import { __ } from '@wordpress/i18n';
import { PanelBody, ToggleControl, Button } from '@wordpress/components';

const SOCIAL_PLATFORMS: { key: string; label: string }[] = [
	{ key: 'facebook', label: 'Facebook' },
	{ key: 'twitter', label: 'Twitter / X' },
	{ key: 'linkedin', label: 'LinkedIn' },
	{ key: 'instagram', label: 'Instagram' },
	{ key: 'youtube', label: 'YouTube' },
	{ key: 'github', label: 'GitHub' },
	{ key: 'website', label: __( 'Website', 'author-profile-blocks' ) },
];

const ContentPanel = ( {
	attributes,
	setAttributes,
	handleClearAuthor,
}: ProfileInspectorProps & { handleClearAuthor: () => void } ) => {
	const {
		authorId,
		showImage,
		showEmail,
		showDescription,
		showRegisteredDate,
		showMoreContent,
		showSocialLinks,
		socialLinksToShow,
		lazyLoad,
		contentTabs,
	} = attributes;

	const toggleSocialPlatform = ( key: string, checked: boolean ) => {
		const current = socialLinksToShow || [];
		setAttributes( {
			socialLinksToShow: checked
				? [ ...current, key ]
				: current.filter( ( p ) => p !== key ),
		} );
	};

	return (
		<>
			{ authorId > 0 && (
				<PanelBody title={ __( 'Author', 'author-profile-blocks' ) } initialOpen={ true }>
					<Button
						isDestructive
						variant="secondary"
						onClick={ handleClearAuthor }
					>
						{ __( 'Clear Selected Author', 'author-profile-blocks' ) }
					</Button>
				</PanelBody>
			) }

			<PanelBody title={ __( 'Display Elements', 'author-profile-blocks' ) } initialOpen={ true }>
				<ToggleControl
					label={ __( 'Show Avatar', 'author-profile-blocks' ) }
					checked={ showImage }
					onChange={ () => setAttributes( { showImage: ! showImage } ) }
				/>

				{ showImage && (
					<ToggleControl
						label={ __( 'Lazy Load Image', 'author-profile-blocks' ) }
						checked={ lazyLoad }
						onChange={ () => setAttributes( { lazyLoad: ! lazyLoad } ) }
					/>
				) }

				<ToggleControl
					label={ __( 'Show Email', 'author-profile-blocks' ) }
					checked={ showEmail }
					onChange={ () => setAttributes( { showEmail: ! showEmail } ) }
				/>

				<ToggleControl
					label={ __( 'Show Description', 'author-profile-blocks' ) }
					checked={ showDescription }
					onChange={ () => setAttributes( { showDescription: ! showDescription } ) }
				/>

				<ToggleControl
					label={ __( 'Show Member Since', 'author-profile-blocks' ) }
					checked={ showRegisteredDate }
					onChange={ () => setAttributes( { showRegisteredDate: ! showRegisteredDate } ) }
				/>

				<ToggleControl
					label={ __( 'Show More Section', 'author-profile-blocks' ) }
					checked={ showMoreContent }
					onChange={ () => setAttributes( { showMoreContent: ! showMoreContent } ) }
				/>

				<ToggleControl
					label={ __( 'Organize Content in Tabs', 'author-profile-blocks' ) }
					checked={ contentTabs }
					onChange={ () => setAttributes( { contentTabs: ! contentTabs } ) }
					help={ __( 'Display author info in tabbed sections', 'author-profile-blocks' ) }
				/>
			</PanelBody>

			<PanelBody title={ __( 'Social Links', 'author-profile-blocks' ) } initialOpen={ false }>
				<ToggleControl
					label={ __( 'Show Social Links', 'author-profile-blocks' ) }
					checked={ showSocialLinks }
					onChange={ () => setAttributes( { showSocialLinks: ! showSocialLinks } ) }
				/>

				{ showSocialLinks && (
					<div style={ { marginTop: '8px' } }>
						{ SOCIAL_PLATFORMS.map( ( { key, label } ) => (
							<ToggleControl
								key={ key }
								label={ label }
								checked={ ( socialLinksToShow || [] ).includes( key ) }
								onChange={ ( checked ) => toggleSocialPlatform( key, checked ) }
							/>
						) ) }
					</div>
				) }
			</PanelBody>
		</>
	);
};

export default ContentPanel;
