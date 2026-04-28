/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { fetchAuthors, fetchAuthorById } from '../services/api';
import type { Author } from '../types';

const useAuthors = ( initialAuthorId = 0 ) => {
	const [ isLoading, setIsLoading ] = useState( false );
	const [ authors, setAuthors ] = useState< Author[] >( [] );
	const [ selectedAuthor, setSelectedAuthor ] = useState< Author | null >( null );

	// Load all authors on mount
	useEffect( () => {
		const loadAuthors = async () => {
			setIsLoading( true );
			try {
				const authorsData = await fetchAuthors();
				setAuthors( authorsData );

				// Find selected author if we have an initialAuthorId
				if ( initialAuthorId ) {
					const selected = authorsData.find(
						( author ) => author.id === initialAuthorId,
					);
					setSelectedAuthor( selected );
				}
			} finally {
				setIsLoading( false );
			}
		};

		void loadAuthors();
	}, [ initialAuthorId ] );

	return {
		authors,
		selectedAuthor,
		setSelectedAuthor,
		isLoading,
	};
};

export default useAuthors;
