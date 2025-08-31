/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { fetchAuthors, fetchAuthorsByIds } from '../services/api';

/**
 * Custom hook for managing multiple authors data
 *
 * @param {Object} options Hook options
 * @param {Array} options.authorIds Array of author IDs to fetch
 * @param {string} options.role Optional role filter
 * @param {number} options.maxAuthors Maximum number of authors to return (0 = unlimited)
 * @return {Object} Authors data and loading state
 */
const useAuthorsList = ({ authorIds = [], role = '', maxAuthors = 0 } = {}) => {
    const [isLoading, setIsLoading] = useState(false);
    const [authors, setAuthors] = useState([]);
    const [error, setError] = useState(null);

    useEffect(() => {
        const loadAuthors = async () => {
            setIsLoading(true);
            setError(null);
            
            try {
                let authorsData = [];

                if (authorIds.length > 0) {
                    // Fetch specific authors by IDs
                    authorsData = await fetchAuthorsByIds(authorIds);
                } else {
                    // Fetch all authors with optional role filter
                    const fetchOptions = {};
                    if (role) {
                        fetchOptions.roles = role;
                    }
                    if (maxAuthors > 0) {
                        fetchOptions.perPage = maxAuthors;
                    }
                    
                    authorsData = await fetchAuthors(fetchOptions);
                }

                // Apply max authors limit if fetching by IDs
                if (maxAuthors > 0 && authorsData.length > maxAuthors) {
                    authorsData = authorsData.slice(0, maxAuthors);
                }

                setAuthors(authorsData);
            } catch (err) {
                setError(err.message || 'Failed to load authors');
                console.error('Error loading authors:', err);
            } finally {
                setIsLoading(false);
            }
        };

        loadAuthors();
    }, [authorIds.join(','), role, maxAuthors]); // Dependencies

    return {
        authors,
        isLoading,
        error
    };
};

export default useAuthorsList;