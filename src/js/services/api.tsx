/**
 * WordPress dependencies
 */
import apiFetch from "@wordpress/api-fetch";

/**
 * Social links interface
 */
export interface SocialLinks {
	facebook?: string;
	twitter?: string;
	linkedin?: string;
	instagram?: string;
	youtube?: string;
	github?: string;
	website?: string;

	[key: string]: string | undefined;
}

/**
 * Plugin settings interface
 */
export interface PluginSettings {
	author_roles: string[];
	avatar_size: number;
	social_platforms: string[];
	show_email: number;
	cache_duration: number;
}

/**
 * Author interface - matches PHP REST API response structure
 */
export interface Author {
	id: number;
	title: string; // display_name from WordPress
	name: string; // alias for title for backward compatibility
	slug: string;
	email: string;
	description: string;
	position?: string; // author_position field
	image: string; // avatar_url
	avatar: string; // alias for image for backward compatibility
	social: SocialLinks;
	registered_date?: string;
	member_since_label?: string;
	role?: string;

	// Add other author properties as needed
	[key: string]: any;
}

/**
 * Fetches authors from the API with enhanced fields
 *
 * @param {Object} options         Optional parameters for the request
 * @param {string} options.roles   Comma-separated list of roles to filter by
 * @param {number} options.perPage Number of authors to fetch per page
 * @return {Promise<Author[]>} Array of author objects with enhanced data
 */
export const fetchAuthors = async (
	options: { roles?: string; perPage?: number } = {},
): Promise<Author[]> => {
	const { roles = "administrator,editor,author,contributor", perPage = 100 } =
		options;

	try {
		const authors = (await apiFetch({
			path: `/wp/v2/users?roles=${roles}&per_page=${perPage}&_embed`,
			method: "GET",
		})) as any[];

		// Transform the response to match our Author interface
		return authors.map((author: any) => ({
			id: author.id,
			title: author.name || "",
			name: author.name || "", // alias for backward compatibility
			slug: author.slug || "",
			email: author.email || "",
			description: author.author_description || "",
			position: author.author_position || "",
			image: author.avatar_url || "",
			avatar: author.avatar_url || "", // alias for backward compatibility
			social: author.social_profiles || {},
			registered_date: author.registered_date || "",
			member_since_label: author.member_since_label || "",
			role: author.role || "",
		}));
	} catch (error) {
		console.error("Error fetching authors:", error);
		return [];
	}
};

/**
 * Fetches a single author by ID with enhanced fields
 *
 * @param {number} id Author ID
 * @return {Promise<Author|null>} Author object or null if not found
 */
export const fetchAuthorById = async (id: number): Promise<Author | null> => {
	if (!id) {
		return null;
	}

	try {
		const author = (await apiFetch({
			path: `/wp/v2/users/${id}?_embed`,
			method: "GET",
		})) as any;

		// Transform the response to match our Author interface
		return {
			id: author.id,
			title: author.name || "",
			name: author.name || "", // alias for backward compatibility
			slug: author.slug || "",
			email: author.email || "",
			description: author.author_description || "",
			position: author.author_position || "",
			image: author.avatar_url || "",
			avatar: author.avatar_url || "", // alias for backward compatibility
			social: author.social_profiles || {},
			registered_date: author.registered_date || "",
			member_since_label: author.member_since_label || "",
			role: author.role || "",
		};
	} catch (error) {
		console.error(`Error fetching author with ID ${id}:`, error);
		return null;
	}
};

/**
 * Fetches authors by their IDs
 *
 * @param {number[]} ids Array of author IDs
 * @return {Promise<Author[]>} Array of author objects
 */
export const fetchAuthorsByIds = async (
	ids: number[] = [],
): Promise<Author[]> => {
	if (!ids.length) {
		return [];
	}

	try {
		const authorPromises = ids.map((id) => fetchAuthorById(id));
		const authors = await Promise.all(authorPromises);
		return authors.filter(Boolean); // Remove null results
	} catch (error) {
		console.error("Error fetching authors by IDs:", error);
		return [];
	}
};

// Cache for plugin settings to avoid repeated API calls
let settingsCache: PluginSettings | null = null;
let settingsCacheTime: number = 0;
const SETTINGS_CACHE_DURATION = 5 * 60 * 1000; // 5 minutes

/**
 * Fetches plugin settings from the REST API with caching
 *
 * @return {Promise<PluginSettings>} Plugin settings object
 */
export const fetchPluginSettings = async (): Promise<PluginSettings> => {
	const now = Date.now();

	// Return cached settings if they're still fresh
	if (settingsCache && now - settingsCacheTime < SETTINGS_CACHE_DURATION) {
		return settingsCache;
	}

	try {
		settingsCache = await apiFetch({
			path: "/author-profile-blocks/v1/settings",
			method: "GET",
		});
		settingsCacheTime = now;
		return settingsCache;
	} catch (error) {
		console.error("Error fetching plugin settings:", error);
		// Return default settings as fallback
		const defaultSettings = {
			author_roles: ["administrator", "editor", "author"],
			avatar_size: 150,
			social_platforms: ["facebook", "twitter", "linkedin", "instagram"],
			show_email: 0,
			cache_duration: 24,
		};

		// Cache default settings too to avoid repeated errors
		settingsCache = defaultSettings;
		settingsCacheTime = now;
		return defaultSettings;
	}
};

/**
 * Gets cached plugin settings synchronously (returns defaults if not loaded)
 *
 * @return {PluginSettings} Plugin settings object
 */
export const getPluginSettings = (): PluginSettings => {
	if (settingsCache) {
		return settingsCache;
	}

	// Return defaults if not cached yet
	return {
		author_roles: ["administrator", "editor", "author"],
		avatar_size: 150,
		social_platforms: ["facebook", "twitter", "linkedin", "instagram"],
		show_email: 0,
		cache_duration: 24,
	};
};

/**
 * Clears the settings cache (useful for testing or forced refresh)
 *
 * @return {void}
 */
export const clearSettingsCache = (): void => {
	settingsCache = null;
	settingsCacheTime = 0;
};
