import type { AdvancedBlockAttributes } from '../types';

export interface AuthorProfileAttributes extends AdvancedBlockAttributes {
	authorId: number;
	showImage: boolean;
	showEmail: boolean;
	showDescription: boolean;
	showRegisteredDate: boolean;
	showMoreContent: boolean;
	moreContent: string;
	backgroundColor: string;
	textAlign: string;
	padding: number;
	// Avatar
	avatarSize: number;
	avatarShape: string;
	avatarBorderWidth: number;
	avatarBorderColor: string;
	avatarBorderRadius: number;
	avatarAlignment: string;
	avatarMargin: number;
	// Name
	nameColor: string;
	nameSize: number;
	nameWeight: string;
	nameAlignment: string;
	nameMargin: number;
	nameTransform: string;
	// Description
	descriptionColor: string;
	descriptionSize: number;
	descriptionLineHeight: number;
	descriptionAlignment: string;
	descriptionMargin: number;
	descriptionStyle: string;
	// Meta
	metaColor: string;
	metaSize: number;
	metaAlignment: string;
	metaMargin: number;
	metaStyle: string;
	metaBold: boolean;
	// Email
	emailLinkColor: string;
	emailHoverColor: string;
	// Social
	showSocialLinks: boolean;
	socialLinksToShow: string[];
	socialIconSize: number;
	socialIconColor: string;
	socialIconHoverColor: string;
	socialIconSpacing: number;
	socialIconAlignment: string;
	socialIconBackground: string;
	socialIconBackgroundHover: string;
	// More content
	moreContentBorderColor: string;
	moreContentPadding: number;
	// Layout
	blockStyle: string;
	contentOrder: string;
	borderWidth: number;
	borderColor: string;
	borderRadius: number;
}

export type SetProfileAttributes = ( attrs: Partial< AuthorProfileAttributes > ) => void;

export interface ProfileInspectorProps {
	attributes: AuthorProfileAttributes;
	setAttributes: SetProfileAttributes;
}
