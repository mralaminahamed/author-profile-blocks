/**
 * WordPress dependencies
 */
import { useState, useEffect } from "@wordpress/element";

/**
 * Internal dependencies
 */
import { fetchAuthors, Author } from "../services/api";

/**
 * Hook return type
 */
interface UseAuthorsReturn {
	authors: Author[];
	selectedAuthor: Author | null;
	setSelectedAuthor: (author: Author | null) => void;
	isLoading: boolean;
}

/**
 * Custom hook for managing authors data
 *
 * @param {number} initialAuthorId Initial author ID if available
 * @return {UseAuthorsReturn} Authors data and functions
 */
const useAuthors = (initialAuthorId = 0): UseAuthorsReturn => {
	const [isLoading, setIsLoading] = useState(false);
	const [authors, setAuthors] = useState<Author[]>([]);
	const [selectedAuthor, setSelectedAuthor] = useState<Author | null>(null);

	// Load all authors on mount
	useEffect(() => {
		const loadAuthors = async () => {
			setIsLoading(true);
			try {
				const authorsData = await fetchAuthors();
				setAuthors(authorsData);

				// Find selected author if we have an initialAuthorId
				if (initialAuthorId) {
					const selected = authorsData.find(
						(author) => author.id === initialAuthorId,
					);
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
		isLoading,
	};
};

export default useAuthors;
