/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { fetchAuthors, fetchAuthorById } from '../services/api';

/**
 * Custom hook for managing authors data
 *
 * @param {number} initialAuthorId Initial author ID if available
 * @return {Object} Authors data and functions
 */
const useAuthors = (initialAuthorId = 0) => {
    const [isLoading, setIsLoading] = useState(false);
    const [authors, setAuthors] = useState([]);
    const [selectedAuthor, setSelectedAuthor] = useState(null);

    // Load all authors on mount
    useEffect(() => {
        const loadAuthors = async () => {
            setIsLoading(true);
            try {
                const authorsData = await fetchAuthors();
                setAuthors(authorsData);

                // Find selected author if we have an initialAuthorId
                if (initialAuthorId) {
                    const selected = authorsData.find(author => author.id === initialAuthorId);
                    setSelectedAuthor(selected);
                }
            } finally {
                setIsLoading(false);
            }
        };

        void loadAuthors();
    }, [initialAuthorId]);

    return {
        authors,
        selectedAuthor,
        setSelectedAuthor,
        isLoading
    };
};

export default useAuthors;