/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	AlignmentToolbar,
	BlockControls,
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';
import { TabPanel } from '@wordpress/components';

/**
 * Internal dependencies
 */
import './editor.scss';
import { useAuthors } from '../../js/hooks';
import AuthorBlockPlaceholder from '../../js/components/AuthorBlockPlaceholder';
import AuthorPreview from './components/AuthorPreview';
import MoreContent from './components/MoreContent';
import {
	ContentPanel,
	StylePanel,
	LayoutPanel,
	AdvancedPanel,
} from './components/inspector';

/**
 * The edit function for the Author Profile block.
 *
 * @param {Object} props               Block properties.
 * @param          props.attributes
 * @param          props.setAttributes
 * @return {JSX.Element} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const {
		authorId,
		showMoreContent,
		moreContent,
		textAlign,
		showImage,
		showEmail,
		showDescription,
		showRegisteredDate,
		backgroundColor,
		padding,
		// Extended attributes
		avatarSize,
		avatarShape,
		avatarBorderWidth,
		avatarBorderColor,
		avatarBorderRadius,
		avatarAlignment,
		avatarMargin,
		nameColor,
		nameSize,
		nameWeight,
		nameTransform,
		nameAlignment,
		nameMargin,
		descriptionColor,
		descriptionSize,
		descriptionLineHeight,
		descriptionStyle,
		descriptionAlignment,
		descriptionMargin,
		metaColor,
		metaSize,
		metaStyle,
		metaBold,
		metaAlignment,
		metaMargin,
		emailLinkColor,
		emailHoverColor,
		showSocialLinks,
		socialLinksToShow,
		socialIconColor,
		socialIconHoverColor,
		socialIconBackground,
		socialIconBackgroundHover,
		socialIconSize,
		socialIconSpacing,
		socialIconAlignment,
		moreContentBorderColor,
		moreContentPadding,
		blockStyle,
		contentOrder,
		customCssClass,
		margin,
		sectionSpacing,
		borderWidth,
		borderColor,
		borderRadius,
		boxShadow,
		boxShadowColor,
		boxShadowHorizontal,
		boxShadowVertical,
		boxShadowBlur,
		boxShadowSpread,
		containerWidth,
		customVar1,
		customVar2,
	} = attributes;

	// Use our custom hook to manage authors
	const { authors, selectedAuthor, setSelectedAuthor, isLoading } =
		useAuthors(authorId);

	// Create advanced shadow style
	const getShadowStyle = () => {
		if (!boxShadow) {
			return undefined;
		}

		const hOffset = boxShadowHorizontal || 0;
		const vOffset = boxShadowVertical || 4;
		const blur = boxShadowBlur || 8;
		const spread = boxShadowSpread || 0;
		const color = boxShadowColor || 'rgba(0,0,0,0.2)';

		return `${hOffset}px ${vOffset}px ${blur}px ${spread}px ${color}`;
	};

	// Create style object for the block
	const blockStyles = {
		backgroundColor: backgroundColor || undefined,
		padding: padding ? `${padding}px` : undefined,
		margin: margin || undefined,
		boxShadow: boxShadow ? getShadowStyle() : undefined,
		borderWidth: borderWidth ? `${borderWidth}px` : undefined,
		borderStyle: borderWidth ? 'solid' : undefined,
		borderColor: borderWidth ? borderColor : undefined,
		borderRadius: borderRadius ? `${borderRadius}px` : undefined,
		width: containerWidth || undefined,
		// Element-specific custom properties
		'--author-section-spacing': sectionSpacing
			? `${sectionSpacing}px`
			: undefined,
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
		// Custom variables
		'--author-profile-custom-var-1': customVar1 || undefined,
		'--author-profile-custom-var-2': customVar2 || undefined,
	};

	// Create className string
	const blockClassName = [
		textAlign ? `has-text-align-${textAlign}` : '',
		blockStyle || '',
		customCssClass || '',
		contentOrder ? `content-order-${contentOrder}` : '',
	]
		.filter(Boolean)
		.join(' ');

	const blockProps = useBlockProps({
		className: blockClassName,
		style: blockStyles,
	});

	// Handle author selection
	const handleSelectAuthor = ([authorId]) => {
		setAttributes({ authorId });
	};

	// Handle clearing the selected author
	const handleClearAuthor = () => {
		setAttributes({ authorId: 0 });
		setSelectedAuthor(null);
	};

	// Handle more content changes
	const handleMoreContentChange = (value) => {
		setAttributes({ moreContent: value });
	};

	return (
		<>
			<BlockControls>
				<AlignmentToolbar
					value={textAlign}
					onChange={(newAlign) =>
						setAttributes({ textAlign: newAlign })
					}
				/>
			</BlockControls>

			<InspectorControls>
				<TabPanel
					className="author-profile-inspector-tabs"
					activeClass="is-active"
					tabs={[
						{
							name: 'content',
							title: __('Content', 'author-profile-blocks'),
							className: 'author-profile-tab-content',
						},
						{
							name: 'style',
							title: __('Style', 'author-profile-blocks'),
							className: 'author-profile-tab-style',
						},
						{
							name: 'layout',
							title: __('Layout', 'author-profile-blocks'),
							className: 'author-profile-tab-layout',
						},
						{
							name: 'advanced',
							title: __('Advanced', 'author-profile-blocks'),
							className: 'author-profile-tab-advanced',
						},
					]}
				>
					{(tab) => {
						if (tab.name === 'content') {
							return (
								<ContentPanel
									attributes={attributes}
									setAttributes={setAttributes}
									handleClearAuthor={handleClearAuthor}
								/>
							);
						} else if (tab.name === 'style') {
							return (
								<StylePanel
									attributes={attributes}
									setAttributes={setAttributes}
								/>
							);
						} else if (tab.name === 'layout') {
							return (
								<LayoutPanel
									attributes={attributes}
									setAttributes={setAttributes}
								/>
							);
						} else if (tab.name === 'advanced') {
							return (
								<AdvancedPanel
									attributes={attributes}
									setAttributes={setAttributes}
								/>
							);
						}
					}}
				</TabPanel>
			</InspectorControls>

			<div {...blockProps}>
				{!authorId ? (
					<AuthorBlockPlaceholder
						icon="admin-users"
						title={__('Select an Author', 'author-profile-blocks')}
						instructions={__(
							'Choose an author to display their profile.',
							'author-profile-blocks'
						)}
						selectedAuthorIds={authorId ? [authorId] : []}
						onChange={handleSelectAuthor}
						buttonLabel={__('Add Author', 'author-profile-blocks')}
						layoutSelector={null}
						additionalControls={null}
					/>
				) : (
					<>
						<AuthorPreview attributes={attributes} />

						{showMoreContent && (
							<MoreContent
								content={moreContent}
								onContentChange={handleMoreContentChange}
							/>
						)}
					</>
				)}
			</div>
		</>
	);
}
