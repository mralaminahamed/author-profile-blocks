/**
 * WordPress dependencies
 */
import ServerSideRender from '@wordpress/server-side-render';

/**
 * AuthorPreview component for showing the selected author
 *
 * @param {Object} props            Component props
 * @param {Object} props.attributes Block attributes
 * @return {JSX.Element} Component to render
 */
const AuthorPreview = ({ attributes }) => {
	const {
		// Avatar settings
		avatarSize,
		avatarShape,
		avatarBorderWidth,
		avatarBorderColor,
		avatarBorderRadius,
		avatarAlignment,
		avatarMargin,
		// Name settings
		nameColor,
		nameSize,
		nameWeight,
		nameTransform,
		nameAlignment,
		nameMargin,
		// Description settings
		descriptionColor,
		descriptionSize,
		descriptionLineHeight,
		descriptionStyle,
		descriptionAlignment,
		descriptionMargin,
		// Meta settings
		metaColor,
		metaSize,
		metaStyle,
		metaBold,
		metaAlignment,
		metaMargin,
		emailLinkColor,
		emailHoverColor,
		// Social settings
		socialIconColor,
		socialIconHoverColor,
		socialIconBackground,
		socialIconBackgroundHover,
		socialIconSize,
		socialIconSpacing,
		socialIconAlignment,
		// More content settings
		moreContentBorderColor,
		moreContentPadding,
		// Layout settings
		contentOrder,
		sectionSpacing,
	} = attributes;

	// Create custom CSS variables to pass to the server-side render
	const customStyles = {
		// Avatar styles
		'--author-avatar-size': avatarSize ? `${avatarSize}px` : undefined,
		'--author-avatar-border-width': avatarBorderWidth
			? `${avatarBorderWidth}px`
			: undefined,
		'--author-avatar-border-color': avatarBorderColor || undefined,
		'--author-avatar-border-radius':
			avatarShape === 'custom' && avatarBorderRadius
				? `${avatarBorderRadius}px`
				: undefined,
		'--author-avatar-align': avatarAlignment || undefined,
		'--author-avatar-margin': avatarMargin
			? `${avatarMargin}px`
			: undefined,
		// Name styles
		'--author-name-size': nameSize ? `${nameSize}px` : undefined,
		'--author-name-weight': nameWeight || undefined,
		'--author-name-color': nameColor || undefined,
		'--author-name-transform': nameTransform || undefined,
		'--author-name-align': nameAlignment || undefined,
		'--author-name-margin': nameMargin ? `${nameMargin}px` : undefined,
		// Description styles
		'--author-description-size': descriptionSize
			? `${descriptionSize}px`
			: undefined,
		'--author-description-line-height': descriptionLineHeight || undefined,
		'--author-description-color': descriptionColor || undefined,
		'--author-description-style': descriptionStyle || undefined,
		'--author-description-align': descriptionAlignment || undefined,
		'--author-description-margin': descriptionMargin
			? `${descriptionMargin}px`
			: undefined,
		// Meta styles
		'--author-meta-size': metaSize ? `${metaSize}px` : undefined,
		'--author-meta-color': metaColor || undefined,
		'--author-meta-style': metaStyle || undefined,
		'--author-meta-weight': metaBold ? 'bold' : undefined,
		'--author-meta-align': metaAlignment || undefined,
		'--author-meta-margin': metaMargin ? `${metaMargin}px` : undefined,
		// Email link styles
		'--author-email-link-color': emailLinkColor || undefined,
		'--author-email-link-hover-color': emailHoverColor || undefined,
		// Social icon styles
		'--author-social-icon-size': socialIconSize
			? `${socialIconSize}px`
			: undefined,
		'--author-social-icon-color': socialIconColor || undefined,
		'--author-social-icon-hover-color': socialIconHoverColor || undefined,
		'--author-social-icon-bg': socialIconBackground || undefined,
		'--author-social-icon-bg-hover': socialIconBackgroundHover || undefined,
		'--author-social-icon-spacing': socialIconSpacing
			? `${socialIconSpacing}px`
			: undefined,
		'--author-social-icon-align': socialIconAlignment || undefined,
		// More content section styles
		'--author-more-content-border-color':
			moreContentBorderColor || undefined,
		'--author-more-content-padding': moreContentPadding
			? `${moreContentPadding}px`
			: undefined,
		// Spacing
		'--author-section-spacing': sectionSpacing
			? `${sectionSpacing}px`
			: undefined,
	};

	// Add data attributes for the server-side renderer
	const dataAttributes = {
		'data-avatar-shape': avatarShape || undefined,
		'data-content-order': contentOrder || undefined,
		'data-meta-bold': metaBold ? 'true' : undefined,
		'data-description-style': descriptionStyle || undefined,
	};

	return (
		<div style={customStyles} {...dataAttributes}>
			<ServerSideRender
				block="author-profile-blocks/author-profile"
				attributes={attributes}
			/>
		</div>
	);
};

export default AuthorPreview;
