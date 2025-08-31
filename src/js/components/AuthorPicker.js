/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { SelectControl, Button, Flex, FlexItem } from '@wordpress/components';
import { useState } from '@wordpress/element';

/**
 * AuthorPicker component for selecting multiple authors
 *
 * @param {Object} props Component props
 * @param {Array} props.authors Available authors list
 * @param {Array} props.selectedAuthors Currently selected authors
 * @param {Function} props.onAuthorsChange Callback when authors change
 * @param {number} props.maxAuthors Maximum number of authors (0 = unlimited)
 * @return {JSX.Element} Component to render
 */
const AuthorPicker = ({
    authors = [],
    selectedAuthors = [],
    onAuthorsChange,
    maxAuthors = 0
}) => {
    const [currentAuthorId, setCurrentAuthorId] = useState('');

    // Filter available authors (exclude already selected)
    const availableAuthors = authors.filter(author => 
        !selectedAuthors.find(selected => selected.id === author.id)
    );

    // Check if we've reached the max limit
    const hasReachedMax = maxAuthors > 0 && selectedAuthors.length >= maxAuthors;

    // Prepare options for select
    const authorOptions = [
        { label: __('Select an author...', 'author-profile-blocks'), value: '' },
        ...availableAuthors.map(author => ({
            label: author.name || author.display_name || `User ${author.id}`,
            value: author.id.toString()
        }))
    ];

    // Handle adding author
    const handleAddAuthor = () => {
        if (!currentAuthorId) return;

        const authorId = parseInt(currentAuthorId);
        const author = authors.find(a => a.id === authorId);
        
        if (author && !selectedAuthors.find(selected => selected.id === authorId)) {
            onAuthorsChange([...selectedAuthors, author]);
            setCurrentAuthorId('');
        }
    };

    // Handle removing author
    const handleRemoveAuthor = (authorId) => {
        const newSelection = selectedAuthors.filter(author => author.id !== authorId);
        onAuthorsChange(newSelection);
    };

    return (
        <div className="apbl-author-picker">
            {!hasReachedMax && availableAuthors.length > 0 && (
                <Flex className="apbl-author-picker-controls">
                    <FlexItem>
                        <SelectControl
                            value={currentAuthorId}
                            options={authorOptions}
                            onChange={setCurrentAuthorId}
                            className="apbl-author-select"
                        />
                    </FlexItem>
                    <FlexItem>
                        <Button
                            variant="secondary"
                            onClick={handleAddAuthor}
                            disabled={!currentAuthorId}
                        >
                            {__('Add Author', 'author-profile-blocks')}
                        </Button>
                    </FlexItem>
                </Flex>
            )}

            {hasReachedMax && (
                <p className="apbl-max-reached">
                    {__('Maximum number of authors reached.', 'author-profile-blocks')}
                </p>
            )}

            {availableAuthors.length === 0 && selectedAuthors.length > 0 && (
                <p className="apbl-all-selected">
                    {__('All available authors have been selected.', 'author-profile-blocks')}
                </p>
            )}

            {selectedAuthors.length > 0 && (
                <div className="apbl-selected-authors">
                    <h4>{__('Selected Authors:', 'author-profile-blocks')}</h4>
                    <ul className="apbl-selected-authors-list">
                        {selectedAuthors.map(author => (
                            <li key={author.id} className="apbl-selected-author">
                                <span className="apbl-author-name">
                                    {author.name || author.display_name || `User ${author.id}`}
                                </span>
                                <Button
                                    variant="link"
                                    isDestructive
                                    onClick={() => handleRemoveAuthor(author.id)}
                                    className="apbl-remove-author"
                                >
                                    {__('Remove', 'author-profile-blocks')}
                                </Button>
                            </li>
                        ))}
                    </ul>
                </div>
            )}

            {authors.length === 0 && (
                <p className="apbl-no-authors">
                    {__('No authors available.', 'author-profile-blocks')}
                </p>
            )}
        </div>
    );
};

export default AuthorPicker;