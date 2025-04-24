/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button, SelectControl, Spinner } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

/**
 * AuthorPicker component for selecting multiple authors
 *
 * @param {Object} props                 Component props
 * @param {Array}  props.selectedAuthorIds List of selected author IDs
 * @param {Function} props.onChange      Callback when authors selection changes
 * @param {string} props.buttonLabel     Optional. Custom label for the add button
 * @param {number} props.perPage         Optional. Number of authors to fetch per page
 * @param {boolean} props.showAvatars    Optional. Whether to show author avatars
 * @return {JSX.Element} Component to render
 */
const AuthorPicker = ({ 
    selectedAuthorIds = [], 
    onChange, 
    buttonLabel,
    perPage = 100,
    showAvatars = true
}) => {
    const [authorId, setAuthorId] = useState('');
    const [isLoading, setIsLoading] = useState(true);

    // Get all authors from WordPress
    const { authors, loadingAuthors } = useSelect((select) => {
        const { getUsers, isResolving } = select(coreStore);
        const query = { per_page: perPage };
        return {
            authors: getUsers(query) || [],
            loadingAuthors: isResolving('getUsers', [query])
        };
    }, [perPage]);

    // Update loading state when data is fetched
    useEffect(() => {
        if (!loadingAuthors) {
            setIsLoading(false);
        }
    }, [loadingAuthors]);

    // Filter out already selected authors
    const availableAuthors = authors.filter(author => !selectedAuthorIds.includes(author.id));

    // Handle author selection
    const handleAddAuthor = () => {
        if (!authorId) return;

        const newSelectedIds = [...selectedAuthorIds, parseInt(authorId)];
        onChange(newSelectedIds);
        setAuthorId(''); // Reset selection
    };

    // Handle author removal
    const handleRemoveAuthor = (id) => {
        const newSelectedIds = selectedAuthorIds.filter(authorId => authorId !== id);
        onChange(newSelectedIds);
    };

    // Get author data by ID
    const getAuthorById = (id) => {
        return authors.find(author => author.id === id);
    };

    if (isLoading) {
        return (
            <div className="apb-loading">
                <Spinner className="apb-spinner" />
                <p>{__('Loading authors...', 'author-profile-blocks')}</p>
            </div>
        );
    }

    // Custom button label or default
    const addButtonLabel = buttonLabel || __('Add Author', 'author-profile-blocks');

    return (
        <div className="apb-author-picker-wrapper">
            {/* Show selected authors */}
            {selectedAuthorIds.length > 0 && (
                <div className="apb-authors-selected-wrapper">
                    <p>{__('Selected Authors:', 'author-profile-blocks')}</p>
                    <div className="apb-selected-authors-list">
                        {selectedAuthorIds.map(id => {
                            const author = getAuthorById(id);
                            if (!author) return null;

                            return (
                                <div key={id} className="apb-selected-author">
                                    {showAvatars && author.avatar_urls && (
                                        <img
                                            src={author.avatar_urls['24']}
                                            alt={author.name}
                                        />
                                    )}
                                    <span>{author.name}</span>
                                    <span
                                        className="apb-remove-author dashicons dashicons-no-alt"
                                        onClick={() => handleRemoveAuthor(id)}
                                        title={__('Remove', 'author-profile-blocks')}
                                    ></span>
                                </div>
                            );
                        })}
                    </div>
                </div>
            )}

            {/* Author selection dropdown */}
            <div className="apb-author-selector-dropdown">
                <SelectControl
                    label={__('Select Author', 'author-profile-blocks')}
                    value={authorId}
                    options={[
                        { label: __('-- Select an author --', 'author-profile-blocks'), value: '' },
                        ...availableAuthors.map(author => ({
                            label: author.name,
                            value: author.id
                        }))
                    ]}
                    onChange={(value) => setAuthorId(value)}
                />
            </div>

            <div className="apb-button-wrapper">
                <Button
                    variant="primary"
                    onClick={handleAddAuthor}
                    disabled={!authorId}
                >
                    {addButtonLabel}
                </Button>
            </div>
        </div>
    );
};

export default AuthorPicker;
