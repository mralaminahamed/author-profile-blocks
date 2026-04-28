/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import type { Author } from '../types';

interface FetchAuthorsOptions {
	roles?: string;
	perPage?: number;
}

export const fetchAuthors = async ( options: FetchAuthorsOptions = {} ): Promise< Author[] > => {
	const { roles = 'administrator,editor,author,contributor', perPage = 100 } = options;

	try {
		return await apiFetch< Author[] >( {
			path: `/wp/v2/users?roles=${ roles }&per_page=${ perPage }`,
			method: 'GET',
		} );
	} catch ( error ) {
		console.error( 'Error fetching authors:', error );
		return [];
	}
};

export const fetchAuthorById = async ( id: number ): Promise< Author | null > => {
	if ( ! id ) {
		return null;
	}

	try {
		return await apiFetch< Author >( {
			path: `/wp/v2/users/${ id }`,
			method: 'GET',
		} );
	} catch ( error ) {
		console.error( `Error fetching author with ID ${ id }:`, error );
		return null;
	}
};

export const fetchAuthorsByIds = async ( ids: number[] = [] ): Promise< Author[] > => {
	if ( ! ids.length ) {
		return [];
	}

	try {
		const authors = await Promise.all( ids.map( ( id ) => fetchAuthorById( id ) ) );
		return authors.filter( ( a ): a is Author => a !== null );
	} catch ( error ) {
		console.error( 'Error fetching authors by IDs:', error );
		return [];
	}
};
