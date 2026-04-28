import type {
	AdvancedBlockAttributes,
	AuthorQueryAttributes,
	DisplayToggles,
	BlockStyleAttributes,
	BorderShadowAttributes,
} from '../types';

export interface AuthorCarouselAttributes
	extends AdvancedBlockAttributes,
		AuthorQueryAttributes,
		DisplayToggles,
		BlockStyleAttributes,
		BorderShadowAttributes {
	slidesToShow: number;
	autoplay: boolean;
	autoplaySpeed: number;
	showDots: boolean;
	showArrows: boolean;
	infinite: boolean;
	slideSpacing: number;
	layout: string;
}

export type SetCarouselAttributes = ( attrs: Partial< AuthorCarouselAttributes > ) => void;

export interface CarouselInspectorProps {
	attributes: AuthorCarouselAttributes;
	setAttributes: SetCarouselAttributes;
}
