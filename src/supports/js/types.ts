export interface Author {
	id: number;
	name: string;
	slug: string;
	description: string;
	email?: string;
	url: string;
	link: string;
	avatar_urls: Record< string, string >;
	/** Plugin's custom REST field — single avatar URL (96px). */
	avatar_url?: string;
	/** Plugin's custom REST field — author position/title from user meta. */
	author_position?: string;
	/** Plugin's custom REST field — rich author bio from user meta. */
	author_description?: string;
	/** Plugin's custom REST field — social profile URLs from user meta. */
	social_profiles?: {
		facebook?: string;
		twitter?: string;
		linkedin?: string;
		instagram?: string;
		youtube?: string;
		github?: string;
		website?: string;
		[ key: string ]: string | undefined;
	};
	/** Plugin's custom REST field — registration date label. */
	member_since_label?: string;
	registered_date?: string;
	meta?: Record< string, unknown >;
}

export interface AuthorsHookResult {
	authors: Author[];
	isLoading: boolean;
	error: string | null;
}

export interface AuthorPlaceholderProps {
	icon: string | JSX.Element;
	title: string;
	instructions: string;
	single?: boolean;
	selectedAuthorIds: number[];
	onChange: ( ids: number[] ) => void;
	buttonLabel: string;
	layoutSelector?: JSX.Element;
	className?: string;
}
