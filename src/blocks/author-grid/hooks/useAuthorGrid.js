/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { fetchAuthors } from '../../../js/services';

/**
 * Custom hook for managing authors data in a grid
 *
 * @param {Array} initialAuthorIds Initial author IDs if available
 * @return {Object} Authors data and functions
 */
const useAuthorGrid = (initialAuthorIds = []) => {
	const [isLoading, setIsLoading] = useState(false);
	const [authors, setAuthors] = useState([]);
	const [selectedAuthorIds, setSelectedAuthorIds] =
		useState(initialAuthorIds);

	// Load all authors on mount
	useEffect(() => {
		const loadAuthors = async () => {
			setIsLoading(true);
			try {
				const authorsData = await fetchAuthors();
				setAuthors(authorsData);
			} catch (error) {
				console.error('Error fetching authors:', error);
			} finally {
				setIsLoading(false);
			}
		};

		loadAuthors();
	}, []);

	// Update selected authors when initialAuthorIds changes
	useEffect(() => {
		if (initialAuthorIds?.length) {
			setSelectedAuthorIds(initialAuthorIds);
		}
	}, [initialAuthorIds]);

	// Get selected authors data
	const getSelectedAuthors = () => {
		return authors.filter((author) =>
			selectedAuthorIds.includes(author.id)
		);
	};

	return {
		authors,
		selectedAuthorIds,
		setSelectedAuthorIds,
		getSelectedAuthors,
		isLoading,
	};
};

export default useAuthorGrid;
