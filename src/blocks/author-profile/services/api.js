/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';

/**
 * Fetches authors from the API
 *
 * @return {Promise<Array>} Array of author objects
 */
export const fetchAuthors = async () => {
    try {
        return await apiFetch({
            path: '/wp/v2/users?roles=administrator,editor,author,contributor',
            method: 'GET',
        });
    } catch (error) {
        console.error('Error fetching authors:', error);
        return [];
    }
};

/**
 * Fetches a single author by ID
 *
 * @param {number} id Author ID
 * @return {Promise<Object|null>} Author object or null if not found
 */
export const fetchAuthorById = async (id) => {
    if (!id) return null;

    try {
        return await apiFetch({
            path: `/wp/v2/users/${id}`,
            method: 'GET',
        });
    } catch (error) {
        console.error(`Error fetching author with ID ${id}:`, error);
        return null;
    }
};
