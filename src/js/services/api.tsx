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
 * Author interface
 */
export interface Author {
	id: number;
	name: string;
	slug: string;
	description: string;
	avatar: string;
	social: SocialLinks;

	// Add other author properties as needed
	[key: string]: any;
}

/**
 * Fetches authors from the API
 *
 * @param {Object} options         Optional parameters for the request
 * @param {string} options.roles   Comma-separated list of roles to filter by
 * @param {number} options.perPage Number of authors to fetch per page
 * @return {Promise<Author[]>} Array of author objects
 */
export const fetchAuthors = async (
	options: { roles?: string; perPage?: number } = {},
): Promise<Author[]> => {
	const { roles = "administrator,editor,author,contributor", perPage = 100 } =
		options;

	try {
		return await apiFetch({
			path: `/wp/v2/users?roles=${roles}&per_page=${perPage}`,
			method: "GET",
		});
	} catch (error) {
		console.error("Error fetching authors:", error);
		return [];
	}
};

/**
 * Fetches a single author by ID
 *
 * @param {number} id Author ID
 * @return {Promise<Author|null>} Author object or null if not found
 */
export const fetchAuthorById = async (id: number): Promise<Author | null> => {
	if (!id) {
		return null;
	}

	try {
		return await apiFetch({
			path: `/wp/v2/users/${id}`,
			method: "GET",
		});
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
		return authors.filter(Boolean) as Author[]; // Remove null results
	} catch (error) {
		console.error("Error fetching authors by IDs:", error);
		return [];
	}
};
