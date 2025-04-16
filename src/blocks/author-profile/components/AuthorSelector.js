/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
    Placeholder,
    Spinner,
    TextControl,
    Button
} from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';

/**
 * AuthorSelector component for selecting an author from a list
 *
 * @param {Object}   props                Component props
 * @param {Array}    props.authors        Array of author objects
 * @param {Function} props.onSelectAuthor Callback for when an author is selected
 * @param {boolean}  props.isLoading      Whether authors are currently loading
 * @return {WPElement} Component to render
 */
const AuthorSelector = ({ authors, onSelectAuthor, isLoading }) => {
    const [searchTerm, setSearchTerm] = useState('');

    // Filter authors based on search term
    const filteredAuthors = authors.filter(author =>
        author.title.rendered.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <Placeholder
            icon="admin-users"
            label={__('Author Profile', 'wp-author-showcase')}
            instructions={__('Search and select an author to display their profile', 'wp-author-showcase')}
            className="wpas-author-selector"
        >
            <TextControl
                label={__('Search Authors', 'wp-author-showcase')}
                value={searchTerm}
                onChange={setSearchTerm}
                placeholder={__('Type author name...', 'wp-author-showcase')}
            />

            {isLoading ? (
                <Spinner />
            ) : filteredAuthors.length > 0 ? (
                <ul className="wpas-author-list">
                    {filteredAuthors.map((author) => (
                        <li key={author.id} className="wpas-author-list-item">
                            <Button
                                variant="secondary"
                                onClick={() => onSelectAuthor(author)}
                            >
                                {author.title.rendered}
                            </Button>
                        </li>
                    ))}
                </ul>
            ) : (
                <div className="wpas-no-results">
                    {__('No authors found', 'wp-author-showcase')}
                </div>
            )}
        </Placeholder>
    );
};

export default AuthorSelector;
