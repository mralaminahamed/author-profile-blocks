/**
 * Internal dependencies
 */
import { useAuthorsList } from '../../../js/hooks';

/**
 * Custom hook wrapper to fetch and manage authors data.
 *
 * @param {Array}  authorIds  Array of author IDs.
 * @param {string} role       Optional. Filter authors by role.
 * @param {number} maxAuthors Optional. Maximum number of authors to return.
 * @return {Object} Authors data and loading state.
 */
export default function useAuthors( authorIds, role = '', maxAuthors = 0 ) {
	return useAuthorsList( {
		authorIds,
		role,
		maxAuthors,
	} );
}
