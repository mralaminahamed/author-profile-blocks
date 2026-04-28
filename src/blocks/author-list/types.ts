import type {
	AdvancedBlockAttributes,
	AuthorQueryAttributes,
	DisplayToggles,
} from '../types';

export interface AuthorListAttributes
	extends AdvancedBlockAttributes,
		AuthorQueryAttributes,
		DisplayToggles {
	displayStyle: 'compact' | 'detailed' | 'minimal';
	listStyle: 'ul' | 'ol';
	enableDividers: boolean;
	dividerColor: string;
	enableRounded: boolean;
	enableHoverEffect: boolean;
	enableBorder: boolean;
	backgroundColor: string;
	itemBackgroundColor: string;
	textAlign: string;
	blockPadding: number;
	itemPadding: number;
	itemSpacing: number;
	borderWidth: number;
	borderColor: string;
	borderRadius: number;
}

export type SetListAttributes = ( attrs: Partial< AuthorListAttributes > ) => void;

export interface ListInspectorProps {
	attributes: AuthorListAttributes;
	setAttributes: SetListAttributes;
}
