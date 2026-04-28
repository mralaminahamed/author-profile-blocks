import type {
	AdvancedBlockAttributes,
	AuthorQueryAttributes,
	DisplayToggles,
	BlockStyleAttributes,
	BorderShadowAttributes,
} from '../types';

export interface AuthorGridAttributes
	extends AdvancedBlockAttributes,
		AuthorQueryAttributes,
		DisplayToggles,
		BlockStyleAttributes,
		BorderShadowAttributes {
	columns: number;
	layout: string;
	itemSpacing: number;
}

export type SetGridAttributes = ( attrs: Partial< AuthorGridAttributes > ) => void;

export interface GridInspectorProps {
	attributes: AuthorGridAttributes;
	setAttributes: SetGridAttributes;
}
