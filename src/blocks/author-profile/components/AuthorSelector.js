/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
    Placeholder,
    Spinner,
    SelectControl,
    Button,
    TextControl
} from '@wordpress/components';
import { useState } from '@wordpress/element';

/**
 * AuthorSelector component for selecting an author from a dropdown with search
 *
 * @param {Object}   props                Component props
 * @param {Array}    props.authors        Array of author objects
 * @param {Function} props.onSelectAuthor Callback for when an author is selected
 * @param {boolean}  props.isLoading      Whether authors are currently loading
 * @return {WPElement} Component to render
 */
const AuthorSelector = ({ authors, onSelectAuthor, isLoading }) => {
    const [selectedAuthorId, setSelectedAuthorId] = useState('');
    const [searchTerm, setSearchTerm] = useState('');

    // Filter authors based on search term
    const filteredAuthors = authors.filter(author =>
        author.title.rendered.toLowerCase().includes(searchTerm.toLowerCase())
    );

    // Prepare options for select control
    const authorOptions = [
        { label: __('Select an author...', 'wp-author-showcase'), value: '' },
        ...filteredAuthors.map(author => ({
            label: author.title.rendered,
            value: author.id.toString()
        }))
    ];

    // Handle selection change
    const handleAuthorChange = (authorId) => {
        setSelectedAuthorId(authorId);

        if (authorId) {
            const selectedAuthor = authors.find(author => author.id.toString() === authorId);
            if (selectedAuthor) {
                onSelectAuthor(selectedAuthor);
            }
        }
    };

    // Handle search input change
    const handleSearchChange = (value) => {
        setSearchTerm(value);
    };

    return (
        <Placeholder
            icon="admin-users"
            label={__('Author Profile', 'wp-author-showcase')}
            instructions={__('Search and select an author to display their profile', 'wp-author-showcase')}
            className="wpas-author-selector"
        >
            {isLoading ? (
                <Spinner />
            ) : authors.length > 0 ? (
                <div className="wpas-author-select-container">
                    <TextControl
                        label={__('Search Authors', 'wp-author-showcase')}
                        value={searchTerm}
                        onChange={handleSearchChange}
                        placeholder={__('Type to filter authors...', 'wp-author-showcase')}
                        className="wpas-author-search"
                    />

                    <SelectControl
                        label={__('Choose Author', 'wp-author-showcase')}
                        value={selectedAuthorId}
                        options={authorOptions}
                        onChange={handleAuthorChange}
                        className="wpas-author-select"
                    />

                    {filteredAuthors.length === 0 && searchTerm !== '' ? (
                        <p className="wpas-no-search-results">
                            {__('No authors match your search. Try a different term.', 'wp-author-showcase')}
                        </p>
                    ) : !selectedAuthorId && (
                        <p className="wpas-author-select-help">
                            {__('Please select an author from the dropdown above.', 'wp-author-showcase')}
                        </p>
                    )}
                </div>
            ) : (
                <div className="wpas-no-results">
                    <p>{__('No authors found. Please create some author profiles first.', 'wp-author-showcase')}</p>
                    <Button
                        variant="secondary"
                        href={`${wpAuthorShowcase?.adminUrl || '/wp-admin/'}post-new.php?post_type=author_profile`}
                        target="_blank"
                    >
                        {__('Create Author Profile', 'wp-author-showcase')}
                    </Button>
                </div>
            )}
        </Placeholder>
    );
};

export default AuthorSelector;
