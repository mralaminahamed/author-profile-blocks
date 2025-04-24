/**
 * WordPress dependencies
 */
import { useSelect } from '@wordpress/data';
import { useState, useEffect } from '@wordpress/element';
import { store as coreStore } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

/**
 * Custom hook to fetch and manage authors data.
 *
 * @param {Array} authorIds Array of author IDs.
 * @param {string} role Optional. Filter authors by role.
 * @param {number} maxAuthors Optional. Maximum number of authors to return.
 * @return {Object} Authors data and loading state.
 */
export default function useAuthors(authorIds, role = '', maxAuthors = 0) {
    const [isLoading, setIsLoading] = useState(true);
    const [authors, setAuthors] = useState([]);
    const [error, setError] = useState('');

    const authorData = useSelect(
        (select) => {
            if (!authorIds || !authorIds.length) {
                return {
                    users: [],
                    isFinished: true,
                };
            }

            const { getEntityRecords, hasFinishedResolution } = select(coreStore);
            const query = { include: authorIds, per_page: 100 };
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
        [authorIds]
    );

    useEffect(() => {
        if (authorData?.users && authorData.isFinished) {
            let filteredAuthors = [...authorData.users];

            // Apply role filter if specified
            if (role) {
                filteredAuthors = filteredAuthors.filter((author) => {
                    return author.roles && author.roles.includes(role);
                });
            }

            // Apply maximum authors limit if specified
            if (maxAuthors > 0 && filteredAuthors.length > maxAuthors) {
                filteredAuthors = filteredAuthors.slice(0, maxAuthors);
            }

            setAuthors(filteredAuthors);
            setIsLoading(false);

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
    }, [authorData, authorIds, role, maxAuthors]);

    return {
        authors,
        isLoading,
        error,
    };
}
