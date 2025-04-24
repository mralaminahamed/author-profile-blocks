/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button, SelectControl, Spinner } from '@wordpress/components';
import { useState } from '@wordpress/element';

/**
 * Authors selector component for selecting multiple authors
 *
 * @param {Object} props             Component props
 * @param {Array}  props.authors     List of available authors
 * @param {Array}  props.selectedIds List of selected author IDs
 * @param {Function} props.onSelectAuthors Callback when authors are selected
 * @param {boolean} props.isLoading  Loading state
 * @return {JSX.Element} Component to render
 */
const AuthorsSelector = ({ authors, selectedIds, onSelectAuthors, isLoading }) => {
    const [authorId, setAuthorId] = useState('');

    // Filter out already selected authors
    const availableAuthors = authors.filter(author => !selectedIds.includes(author.id));

    // Handle author selection
    const handleAddAuthor = () => {
        if (!authorId) return;

        const newSelectedIds = [...selectedIds, parseInt(authorId)];
        onSelectAuthors(newSelectedIds);
        setAuthorId(''); // Reset selection
    };

    // Handle author removal
    const handleRemoveAuthor = (id) => {
        const newSelectedIds = selectedIds.filter(authorId => authorId !== id);
        onSelectAuthors(newSelectedIds);
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

    return (
        <div className="apb-author-selector">
            <h3>{__('Select Authors for Carousel', 'author-profile-blocks')}</h3>

            {/* Show selected authors */}
            {selectedIds.length > 0 && (
                <div className="apb-authors-selected-wrapper">
                    <p>{__('Selected Authors:', 'author-profile-blocks')}</p>
                    <div className="apb-selected-authors-list">
                        {selectedIds.map(id => {
                            const author = getAuthorById(id);
                            if (!author) return null;

                            return (
                                <div key={id} className="apb-selected-author">
                                    {author.avatar_urls && (
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
                    label={__('Add Author', 'author-profile-blocks')}
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
                    {__('Add Author to Carousel', 'author-profile-blocks')}
                </Button>
            </div>
        </div>
    );
};

export default AuthorsSelector;
