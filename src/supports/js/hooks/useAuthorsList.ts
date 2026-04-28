/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { fetchAuthors, fetchAuthorsByIds } from '../services';
import type { Author, AuthorsHookResult } from '../types';

interface UseAuthorsListOptions {
	authorIds?: number[];
	role?: string;
	maxAuthors?: number;
}

const useAuthorsList = ( { authorIds = [], role = '', maxAuthors = 0 }: UseAuthorsListOptions = {} ): AuthorsHookResult => {
	const [ isLoading, setIsLoading ] = useState( false );
	const [ authors, setAuthors ] = useState< Author[] >( [] );
	const [ error, setError ] = useState< string | null >( null );

	useEffect( () => {
		const loadAuthors = async () => {
			setIsLoading( true );
			setError( null );

			try {
				let authorsData = [];

				if ( authorIds.length > 0 ) {
					// Fetch specific authors by IDs
					authorsData = await fetchAuthorsByIds( authorIds );
				} else {
					// Fetch all authors with optional role filter
					const fetchOptions = {};
					if ( role ) {
						fetchOptions.roles = role;
					}
					if ( maxAuthors > 0 ) {
						fetchOptions.perPage = maxAuthors;
					}

					authorsData = await fetchAuthors( fetchOptions );
				}

				// Apply max authors limit if fetching by IDs
				if ( maxAuthors > 0 && authorsData.length > maxAuthors ) {
					authorsData = authorsData.slice( 0, maxAuthors );
				}

				setAuthors( authorsData );
			} catch ( err ) {
				setError( ( err as Error ).message || 'Failed to load authors' );
				console.error( 'Error loading authors:', err );
			} finally {
				setIsLoading( false );
			}
		};

		loadAuthors();
	}, [ authorIds.join( ',' ), role, maxAuthors ] ); // Dependencies

	return {
		authors,
		isLoading,
		error,
	};
};

export default useAuthorsList;
