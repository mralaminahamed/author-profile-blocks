export interface Settings {
	author_roles: string[];
	avatar_size: number;
	social_platforms: string[];
	show_email: boolean;
	cache_duration: number;
}

export interface WPPlugin {
	name: string;
	slug: string;
	version: string;
	short_description: string;
	icons?: { '1x'?: string; '2x'?: string; svg?: string };
	rating: number;
	num_ratings: number;
	active_installs: number;
}

declare global {
	interface Window {
		apblAdmin?: {
			restUrl: string;
			restNonce: string;
			version: string;
			wpRoles: Record< string, string >;
		};
	}
}
