/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import {
	fetchAuthors,
	fetchAuthorsByIds,
	fetchPluginSettings,
	Author,
	PluginSettings,
} from '../services';

/**
 * Hook return type
 */
interface UseAuthorsListReturn {
	authors: Author[];
	isLoading: boolean;
	error: string | null;
	pluginSettings: PluginSettings | null;
}

/**
 * Custom hook for managing multiple authors data
 */
interface UseAuthorsListOptions {
	authorIds?: number[];
	role?: string;
	maxAuthors?: number;
}

const useAuthorsList = ({
	authorIds = [],
	role = '',
	maxAuthors = 0,
}: UseAuthorsListOptions = {}): UseAuthorsListReturn => {
	const [ isLoading, setIsLoading ] = useState(false);
	const [ authors, setAuthors ] = useState<Author[]>([]);
	const [ error, setError ] = useState<string | null>(null);
	const [ pluginSettings, setPluginSettings ] = useState<PluginSettings | null>(
		null,
	);

	useEffect(() => {
		const loadAuthors = async () => {
			setIsLoading(true);
			setError(null);

			try {
				// Fetch plugin settings first
				const settings = await fetchPluginSettings();
				setPluginSettings(settings);

				let authorsData = [];

				if (authorIds.length > 0) {
					// Fetch specific authors by IDs
					authorsData = await fetchAuthorsByIds(authorIds);
				} else {
					// Fetch all authors with optional role filter
					const fetchOptions: { roles?: string; perPage?: number } =
						{};
					if (role) {
						fetchOptions.roles = role;
					} else {
						// Use configured roles from settings if no specific role provided
						fetchOptions.roles = settings.author_roles.join(',');
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

		void loadAuthors();
	}, [ authorIds.join(','), role, maxAuthors ]); // Dependencies

	return {
		authors,
		isLoading,
		error,
		pluginSettings,
	};
};

export default useAuthorsList;
