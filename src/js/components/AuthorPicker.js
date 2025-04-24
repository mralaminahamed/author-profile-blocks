/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { sprintf } from '@wordpress/i18n';
import { 
    Button, 
    SelectControl, 
    Spinner, 
    TextControl,
    Icon,
    Notice,
    Flex,
    FlexItem,
    Tooltip
} from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { search, people, info } from '@wordpress/icons';

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
    const [searchTerm, setSearchTerm] = useState('');

    // Get all authors from WordPress
    const { authors, loadingAuthors } = useSelect((select) => {
        const { getUsers, isResolving } = select(coreStore);
        const query = { per_page: perPage, who: 'authors' };
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

    // Filter authors based on search term
    const filteredAuthors = authors.filter(author => 
        author.name.toLowerCase().includes(searchTerm.toLowerCase())
    );

    // Filter out already selected authors from the available options
    const availableAuthors = filteredAuthors.filter(author => 
        !selectedAuthorIds.includes(author.id)
    );

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

    // Clear search
    const clearSearch = () => {
        setSearchTerm('');
    };

    // Handle key press in search field
    const handleSearchKeyPress = (e) => {
        if (e.key === 'Enter' && authorId) {
            handleAddAuthor();
            e.preventDefault();
        }
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
            <div className="apb-author-header">
                <Flex justify="space-between" align="center">
                    <FlexItem>
                        <Flex align="center" gap={2}>
                            <Icon icon={people} size={24} />
                            <h4>{__('Author Selection', 'author-profile-blocks')}</h4>
                        </Flex>
                    </FlexItem>
                    <FlexItem>
                        <div className="apb-author-count">
                            <span>{authors.length}</span>
                            <Tooltip text={__('Total number of available authors', 'author-profile-blocks')}>
                                <Icon icon={info} size={16} />
                            </Tooltip>
                        </div>
                    </FlexItem>
                </Flex>
            </div>

            <div className="apb-author-content">
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

                {/* Search field */}
                <div className="apb-search-field">
                    <Icon icon={search} className="apb-search-icon" />
                    <TextControl
                        value={searchTerm}
                        onChange={setSearchTerm}
                        placeholder={__('Search authors...', 'author-profile-blocks')}
                        className="apb-author-search"
                        onKeyDown={handleSearchKeyPress}
                    />
                    {searchTerm && (
                        <Button
                            className="apb-clear-search"
                            isSmall
                            isSecondary
                            onClick={clearSearch}
                            aria-label={__('Clear search', 'author-profile-blocks')}
                        >
                            <span className="dashicons dashicons-no-alt"></span>
                        </Button>
                    )}
                </div>

                {/* Author selection dropdown */}
                <div className="apb-select-field">
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
                        className="apb-author-select"
                        __nextHasNoMarginBottom
                    />
                </div>

                {/* Filter information */}
                {filteredAuthors.length === 0 && searchTerm !== '' ? (
                    <Notice
                        className="apb-notice"
                        status="warning"
                        isDismissible={false}
                    >
                        {__('No authors match your search criteria.', 'author-profile-blocks')}
                    </Notice>
                ) : (filteredAuthors.length < authors.length && searchTerm !== '') && (
                    <div className="apb-filter-info">
                        <Icon icon={info} size={16} />
                        <span>
                            {sprintf(
                                /* translators: %1$d: filtered authors count, %2$d: total authors count */
                                __('Showing %1$d of %2$d authors', 'author-profile-blocks'),
                                filteredAuthors.length,
                                authors.length
                            )}
                        </span>
                    </div>
                )}
            </div>

            <div className="apb-author-footer">
                <Flex justify="space-between" align="center">
                    <FlexItem>
                        {selectedAuthorIds.length > 0 && (
                            <Button
                                variant="tertiary"
                                onClick={() => onChange([])}
                                className="apb-reset-btn"
                            >
                                {__('Clear All', 'author-profile-blocks')}
                            </Button>
                        )}
                    </FlexItem>
                    <FlexItem>
                        <Button
                            variant="primary"
                            onClick={handleAddAuthor}
                            disabled={!authorId}
                            className="apb-add-author-btn"
                        >
                            {addButtonLabel}
                        </Button>
                    </FlexItem>
                </Flex>
            </div>
        </div>
    );
};

export default AuthorPicker;
