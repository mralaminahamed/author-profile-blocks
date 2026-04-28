/** Advanced attributes shared by all four blocks. */
export interface AdvancedBlockAttributes {
	layoutPreset: string;
	animationType: string;
	animationDuration: number;
	hoverEffect: string;
	customCssClass: string;
	googleFont: string;
	fontSizeUnit: string;
	gradientBackground: boolean;
	gradientStartColor: string;
	gradientEndColor: string;
	gradientDirection: string;
	transformScale: number;
	transformRotate: number;
	filterBrightness: number;
	filterContrast: number;
	filterSaturate: number;
	lazyLoad: boolean;
	contentTabs: boolean;
	tabLabels: { bio: string; contact: string; social: string };
	margin: string;
	sectionSpacing: number;
	boxShadow: boolean;
	boxShadowColor: string;
	boxShadowBlur: number;
	boxShadowSpread: number;
	boxShadowHorizontal: number;
	boxShadowVertical: number;
	containerWidth: string;
	customVar1: string;
	customVar2: string;
}

/** Border + shadow attributes shared by grid/list/carousel. */
export interface BorderShadowAttributes {
	borderWidth: number;
	borderColor: string;
	borderRadius: number;
	enableBorder: boolean;
	enableRounded: boolean;
	enableShadow: boolean;
}

/** Shared display toggles present in multi-author blocks. */
export interface DisplayToggles {
	showImage: boolean;
	showEmail: boolean;
	showDescription: boolean;
	showPosition: boolean;
	showSocial: boolean;
	showRegisteredDate: boolean;
}

/** Shared author-query attributes for multi-author blocks. */
export interface AuthorQueryAttributes {
	authorIds: number[];
	authorRole: string;
	maxAuthors: number;
}

/** Shared style props: bg, text-align, padding. */
export interface BlockStyleAttributes {
	backgroundColor: string;
	textAlign: string;
	padding: number;
}
