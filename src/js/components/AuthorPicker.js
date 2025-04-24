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
    Tooltip,
    Card,
    Badge,
    Animate,
    SearchControl
} from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { search, people, info, plus, closeSmall } from '@wordpress/icons';

/**
 * Enhanced AuthorPicker component for selecting multiple authors
 *
 * @param {Object} props                 Component props
 * @param {Array}  props.selectedAuthorIds List of selected author IDs
 * @param {Function} props.onChange      Callback when authors selection changes
 * @param {string} props.buttonLabel     Optional. Custom label for the add button
 * @param {number} props.perPage         Optional. Number of authors to fetch per page
 * @param {boolean} props.showAvatars    Optional. Whether to show author avatars
 * @return {JSX.Element} Component to render
 */
const AuthorPicker = ({ selectedAuthorIds = [], onChange, buttonLabel, perPage = 100, showAvatars = true}) => {
    const [authorId, setAuthorId] = useState('');
    const [isLoading, setIsLoading] = useState(true);
    const [searchTerm, setSearchTerm] = useState('');
    const [showDropdown, setShowDropdown] = useState(false);

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
        setShowDropdown(false);
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

    // Handle direct author selection from search results
    const handleDirectSelect = (id) => {
        const newSelectedIds = [...selectedAuthorIds, parseInt(id)];
        onChange(newSelectedIds);
        setSearchTerm('');
        setShowDropdown(false);
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
                            <div className="apb-author-header-icon">
                                <Icon icon={people} size={20} />
                            </div>
                            <h4>{__('Author Selection', 'author-profile-blocks')}</h4>
                        </Flex>
                    </FlexItem>
                    <FlexItem>
                        <div className="apb-author-count">
                            <Badge>{authors.length}</Badge>
                            <Tooltip text={__('Total number of available authors', 'author-profile-blocks')}>
                                <Icon icon={info} size={16} />
                            </Tooltip>
                        </div>
                    </FlexItem>
                </Flex>
            </div>

            <div className="apb-author-content">
                {/* Show selected authors */}
                <div className="apb-authors-selected-wrapper">
                    <h5>{__('Selected Authors', 'author-profile-blocks')}</h5>
                    <div className="apb-selected-authors-list">
                        {selectedAuthorIds.length > 0 ? (
                            selectedAuthorIds.map(id => {
                                const author = getAuthorById(id);
                                if (!author) return null;

                                return (
                                    <Animate key={id} type="appear">
                                        <div className="apb-selected-author">
                                            {showAvatars && author.avatar_urls && (
                                                <img
                                                    src={author.avatar_urls['24']}
                                                    alt={author.name}
                                                />
                                            )}
                                            <span>{author.name}</span>
                                            <button
                                                className="apb-remove-author"
                                                onClick={() => handleRemoveAuthor(id)}
                                                title={__('Remove', 'author-profile-blocks')}
                                                aria-label={sprintf(__('Remove %s', 'author-profile-blocks'), author.name)}
                                            >
                                                <Icon icon={closeSmall} size={20} />
                                            </button>
                                        </div>
                                    </Animate>
                                );
                            })
                        ) : (
                            <div className="apb-no-authors-selected">
                                <p>{__('No authors selected yet', 'author-profile-blocks')}</p>
                            </div>
                        )}
                    </div>
                </div>

                {/* Search field */}
                <div className="apb-search-field">
                    <h5>{__('Find Authors', 'author-profile-blocks')}</h5>
                    <SearchControl
                        value={searchTerm}
                        onChange={(value) => {
                            setSearchTerm(value);
                            setShowDropdown(value.length > 0);
                        }}
                        placeholder={__('Search by name...', 'author-profile-blocks')}
                        className="apb-author-search"
                        onKeyDown={handleSearchKeyPress}
                    />

                    {/* Search results dropdown */}
                    {showDropdown && searchTerm && (
                        <div className="apb-search-results">
                            {availableAuthors.length > 0 ? (
                                <ul>
                                    {availableAuthors.slice(0, 5).map(author => (
                                        <li
                                            key={author.id}
                                            onClick={() => handleDirectSelect(author.id)}
                                        >
                                            {showAvatars && author.avatar_urls && (
                                                <img
                                                    src={author.avatar_urls['24']}
                                                    alt={author.name}
                                                />
                                            )}
                                            <span>{author.name}</span>
                                            <Icon icon={plus} size={16} />
                                        </li>
                                    ))}
                                </ul>
                            ) : (
                                <p className="apb-no-results">{__('No matching authors found', 'author-profile-blocks')}</p>
                            )}
                        </div>
                    )}

                    {/* Select fallback when there are many results */}
                    {availableAuthors.length > 5 && searchTerm && (
                        <div className="apb-select-field">
                            <SelectControl
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
                    )}

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
                            icon={plus}
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