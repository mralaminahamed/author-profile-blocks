/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';

/**
 * Fetches authors from the API
 *
 * @param {Object} options Optional parameters for the request
 * @param {string} options.roles Comma-separated list of roles to filter by
 * @param {number} options.perPage Number of authors to fetch per page
 * @return {Promise<Array>} Array of author objects
 */
export const fetchAuthors = async (options = {}) => {
    const { 
        roles = 'administrator,editor,author,contributor', 
        perPage = 100 
    } = options;
    
    try {
        return await apiFetch({
            path: `/wp/v2/users?roles=${roles}&per_page=${perPage}`,
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

/**
 * Fetches authors by their IDs
 *
 * @param {Array} ids Array of author IDs
 * @return {Promise<Array>} Array of author objects
 */
export const fetchAuthorsByIds = async (ids = []) => {
    if (!ids.length) return [];
    
    try {
        const authorPromises = ids.map(id => fetchAuthorById(id));
        const authors = await Promise.all(authorPromises);
        return authors.filter(Boolean); // Remove null results
    } catch (error) {
        console.error('Error fetching authors by IDs:', error);
        return [];
    }
};