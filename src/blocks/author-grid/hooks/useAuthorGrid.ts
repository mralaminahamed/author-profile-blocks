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
} from '../../../js/services';

/**
 * Hook return type
 */
interface UseAuthorGridReturn {
	authors: Author[];
	selectedAuthorIds: number[];
	setSelectedAuthorIds: (ids: number[]) => void;
	getSelectedAuthors: () => Author[];
	isLoading: boolean;
	pluginSettings: PluginSettings | null;
}

/**
 * Custom hook for managing authors data in a grid
 *
 * @param {number[]} initialAuthorIds Initial author IDs if available
 * @return {UseAuthorGridReturn} Authors data and functions
 */
const useAuthorGrid = (
	initialAuthorIds: number[] = [],
): UseAuthorGridReturn => {
	const [ isLoading, setIsLoading ] = useState(false);
	const [ authors, setAuthors ] = useState<Author[]>([]);
	const [ selectedAuthorIds, setSelectedAuthorIds ] =
		useState(initialAuthorIds);
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
			} catch (error) {
				console.error('Error fetching data:', error);
			} finally {
				setIsLoading(false);
			}
		};

		loadData();
	}, []);

	// Update selected authors when initialAuthorIds changes
	useEffect(() => {
		if (initialAuthorIds?.length) {
			setSelectedAuthorIds(initialAuthorIds);
		}
	}, [ initialAuthorIds ]);

	// Get selected authors data
	const getSelectedAuthors = () => {
		return authors.filter((author) =>
			selectedAuthorIds.includes(author.id),
		);
	};

	return {
		authors,
		selectedAuthorIds,
		setSelectedAuthorIds,
		getSelectedAuthors,
		isLoading,
		pluginSettings,
	};
};

export default useAuthorGrid;
