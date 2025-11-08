/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import {
	fetchAuthors,
	fetchPluginSettings,
	Author,
	PluginSettings,
} from '../services/api';

/**
 * Hook return type
 */
interface UseAuthorsReturn {
	authors: Author[];
	selectedAuthor: Author | null;
	setSelectedAuthor: (author: Author | null) => void;
	isLoading: boolean;
	pluginSettings: PluginSettings | null;
}

/**
 * Custom hook for managing authors data
 *
 * @param {number} initialAuthorId Initial author ID if available
 * @return {UseAuthorsReturn} Authors data and functions
 */
const useAuthors = (initialAuthorId = 0): UseAuthorsReturn => {
	const [ isLoading, setIsLoading ] = useState(false);
	const [ authors, setAuthors ] = useState<Author[]>([]);
	const [ selectedAuthor, setSelectedAuthor ] = useState<Author | null>(null);
	const [ pluginSettings, setPluginSettings ] = useState<PluginSettings | null>(
		null,
	);

	// Load plugin settings and authors on mount
	useEffect(() => {
		const loadData = async () => {
			setIsLoading(true);
			try {
				// Fetch plugin settings first
				const settings = await fetchPluginSettings();
				setPluginSettings(settings);

				// Fetch authors using the configured roles from settings
				const roles = settings.author_roles.join(',');
				const authorsData = await fetchAuthors({ roles });
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

		void loadData();
	}, [ initialAuthorId ]);

	return {
		authors,
		selectedAuthor,
		setSelectedAuthor,
		isLoading,
		pluginSettings,
	};
};

export default useAuthors;
