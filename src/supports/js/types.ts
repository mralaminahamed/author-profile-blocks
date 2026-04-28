export interface Author {
	id: number;
	name: string;
	slug: string;
	description: string;
	email: string;
	url: string;
	link: string;
	avatar_urls: Record< string, string >;
	meta?: {
		position?: string;
		facebook?: string;
		twitter?: string;
		linkedin?: string;
		instagram?: string;
		youtube?: string;
		github?: string;
		website?: string;
		[ key: string ]: string | undefined;
	};
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
