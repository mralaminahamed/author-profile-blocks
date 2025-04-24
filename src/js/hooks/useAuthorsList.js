/**
 * WordPress dependencies
 */
import { useSelect } from '@wordpress/data';
import { useState, useEffect } from '@wordpress/element';
import { store as coreStore } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

/**
 * Custom hook to fetch and manage multiple authors data.
 *
 * @param {Object} options Hook options
 * @param {Array} options.authorIds Array of author IDs.
 * @param {string} options.role Optional. Filter authors by role.
 * @param {number} options.maxAuthors Optional. Maximum number of authors to return.
 * @param {number} options.perPage Optional. Number of authors to fetch per page.
 * @param {string} options.orderBy Optional. Field to sort authors by.
 * @param {string} options.order Optional. Sort order (asc/desc).
 * @return {Object} Authors data and loading state.
 */
const useAuthorsList = ({
    authorIds = [],
    role = '',
    maxAuthors = 0,
    perPage = 100,
    orderBy = 'name',
    order = 'asc'
}) => {
    const [isLoading, setIsLoading] = useState(true);
    const [authors, setAuthors] = useState([]);
    const [error, setError] = useState('');

    // Fetch authors by IDs
    const authorData = useSelect(
        (select) => {
            if (!authorIds || !authorIds.length) {
                return {
                    users: [],
                    isFinished: true,
                };
            }

            const { getEntityRecords, hasFinishedResolution } = select(coreStore);
            const query = { include: authorIds, per_page: perPage };
            const users = getEntityRecords('root', 'user', query);
            const isFinished = hasFinishedResolution('getEntityRecords', [
                'root',
                'user',
                query,
            ]);

            return {
                users,
                isFinished,
            };
        },
        [authorIds, perPage]
    );

    // Process the fetched authors
    useEffect(() => {
        if (authorData?.users && authorData.isFinished) {
            let filteredAuthors = [...authorData.users];

            // Apply role filter if specified
            if (role) {
                filteredAuthors = filteredAuthors.filter((author) => {
                    return author.roles && author.roles.includes(role);
                });
            }

            // Sort authors if needed
            if (orderBy && order) {
                filteredAuthors.sort((a, b) => {
                    let valueA = a[orderBy] || '';
                    let valueB = b[orderBy] || '';
                    
                    // Handle string comparison
                    if (typeof valueA === 'string') {
                        valueA = valueA.toLowerCase();
                    }
                    if (typeof valueB === 'string') {
                        valueB = valueB.toLowerCase();
                    }
                    
                    if (valueA < valueB) return order === 'asc' ? -1 : 1;
                    if (valueA > valueB) return order === 'asc' ? 1 : -1;
                    return 0;
                });
            }

            // Apply maximum authors limit if specified
            if (maxAuthors > 0 && filteredAuthors.length > maxAuthors) {
                filteredAuthors = filteredAuthors.slice(0, maxAuthors);
            }

            setAuthors(filteredAuthors);
            setIsLoading(false);

            // Set error message if no authors found
            if (filteredAuthors.length === 0 && authorIds.length > 0) {
                if (role) {
                    setError(
                        __('No authors found with the selected role.', 'author-profile-blocks')
                    );
                } else {
                    setError(
                        __('No authors found with the selected IDs.', 'author-profile-blocks')
                    );
                }
            } else {
                setError('');
            }
        } else if (authorData?.isFinished) {
            setIsLoading(false);
            if (authorIds.length > 0) {
                setError(
                    __('No authors found with the selected IDs.', 'author-profile-blocks')
                );
            }
        } else {
            setIsLoading(true);
            setError('');
        }
    }, [authorData, authorIds, role, maxAuthors, orderBy, order]);

    return {
        authors,
        isLoading,
        error,
    };
};

export default useAuthorsList;
