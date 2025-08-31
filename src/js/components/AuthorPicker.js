/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';
import {
    Animate,
    Button,
    Flex,
    FlexItem,
    Icon,
    Notice,
    SearchControl,
    SelectControl,
    Spinner,
    Tooltip
} from '@wordpress/components';
import { useEffect, useState, useCallback, useMemo } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { closeSmall, info, people, plus } from '@wordpress/icons';

/**
 * Author result item component
 *
 * @param {Object} props Component props
 * @param {Object} props.author Author data
 * @param {Function} props.onSelect Function to call when selected
 * @param {boolean} props.showAvatar Whether to show author avatar
 * @return {JSX.Element} Author result item
 */
const AuthorResultItem = ({ author, onSelect, showAvatar }) => (
    <li onClick={() => onSelect(author.id)}>
        {showAvatar && author.avatar_urls && (
            <img
                src={author.avatar_urls['24']}
                alt=""
                aria-hidden="true"
            />
        )}
        <span>{author.name}</span>
        <Icon icon={plus} size={16} />
    </li>
);

/**
 * Selected author chip component
 *
 * @param {Object} props Component props
 * @param {Object} props.author Author data
 * @param {Function} props.onRemove Function to call when removed
 * @param {boolean} props.showAvatar Whether to show author avatar
 * @return {JSX.Element} Selected author chip
 */
const SelectedAuthorChip = ({ author = {}, onRemove, showAvatar }) => (
    <Animate type="appear">
        { () =>(
            <div className="apb-selected-author">
                {showAvatar && author.avatar_urls && (
                    <img
                        src={author.avatar_urls['24']}
                        alt=""
                        aria-hidden="true"
                    />
                )}
                <span>{author.name}</span>
                <button
                    className="apb-remove-author"
                    onClick={() => onRemove(author.id)}
                    title={__('Remove', 'author-profile-blocks')}
                    aria-label={sprintf(__('Remove %s', 'author-profile-blocks'), author.name)}
                >
                    <Icon icon={closeSmall} size={20} />
                </button>
            </div>
        )}
    </Animate>
);

/**
 * Enhanced AuthorPicker component for selecting multiple authors
 *
 * @param {Object} props Component props
 * @param {Array} props.selectedAuthorIds List of selected author IDs
 * @param {Function} props.onChange Callback when authors selection changes
 * @param {string} props.buttonLabel Optional. Custom label for the add button
 * @param {number} props.perPage Optional. Number of authors to fetch per page
 * @param {boolean} props.showAvatars Optional. Whether to show author avatars
 * @return {JSX.Element} Component to render
 */
const AuthorPicker = ({
    selectedAuthorIds = [],
    onChange,
    buttonLabel,
    perPage = 100,
    showAvatars = true
}) => {
    // State management
    const [authorId, setAuthorId] = useState('');
    const [isLoading, setIsLoading] = useState(true);
    const [searchTerm, setSearchTerm] = useState('');
    const [showDropdown, setShowDropdown] = useState(false);

    // Fetch authors data from WordPress
    const { authors, loadingAuthors, totalAuthors } = useSelect((select) => {
        const { getUsers, isResolving, getEntityRecords } = select(coreStore);
        const query = { per_page: perPage, who: 'authors' };

        const usersList = getUsers(query) || [];
        const totalUsers = getEntityRecords('root', 'user')?.length || usersList.length;

        return {
            authors: usersList,
            loadingAuthors: isResolving('getUsers', [query]),
            totalAuthors: totalUsers
        };
    }, [perPage]);

    // Update loading state when data is fetched
    useEffect(() => {
        if (!loadingAuthors) {
            setIsLoading(false);
        }
    }, [loadingAuthors]);

    // Filter authors based on search term - memoized for performance
    const filteredAuthors = useMemo(() => {
        if (!searchTerm) return authors;
        return authors.filter(author =>
            author.name.toLowerCase().includes(searchTerm.toLowerCase())
        );
    }, [authors, searchTerm]);

    // Filter out already selected authors from the available options - memoized for performance
    const availableAuthors = useMemo(() => {
        return filteredAuthors.filter(author =>
            !selectedAuthorIds.includes(author.id)
        );
    }, [filteredAuthors, selectedAuthorIds]);

    // Create a map of selected authors for quick lookup - memoized for performance
    const selectedAuthorsMap = useMemo(() => {
        const authorsMap = new Map();
        authors.forEach(author => {
            if (selectedAuthorIds.includes(author.id)) {
                authorsMap.set(author.id, author);
            }
        });
        return authorsMap;
    }, [authors, selectedAuthorIds]);

    // Handle author selection from dropdown
    const handleAddAuthor = useCallback(() => {
        if (!authorId) return;

        const newSelectedIds = [...selectedAuthorIds, parseInt(authorId)];
        onChange(newSelectedIds);
        setAuthorId(''); // Reset selection
        setShowDropdown(false);
    }, [authorId, selectedAuthorIds, onChange]);

    // Handle author removal
    const handleRemoveAuthor = useCallback((id) => {
        const newSelectedIds = selectedAuthorIds.filter(authorId => authorId !== id);
        onChange(newSelectedIds);
    }, [selectedAuthorIds, onChange]);

    // Handle direct author selection from search results
    const handleDirectSelect = useCallback((id) => {
        const newSelectedIds = [...selectedAuthorIds, parseInt(id)];
        onChange(newSelectedIds);
        setSearchTerm('');
        setShowDropdown(false);
    }, [selectedAuthorIds, onChange]);

    // Handle search input changes
    const handleSearchChange = useCallback((value) => {
        setSearchTerm(value);
        setShowDropdown(value.length > 0);
    }, []);

    // Handle key press in search field
    const handleSearchKeyPress = useCallback((e) => {
        if (e.key === 'Enter' && authorId) {
            handleAddAuthor();
            e.preventDefault();
        }
    }, [authorId, handleAddAuthor]);

    // Handle clearing all selected authors
    const handleClearAll = useCallback(() => {
        onChange([]);
    }, [onChange]);

    // Render loading state
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
            {/* Header */}
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
                            <Tooltip text={__('Total number of available authors', 'author-profile-blocks')}>
                                <Icon icon={info} size={16} />
                            </Tooltip>
                        </div>
                    </FlexItem>
                </Flex>
            </div>

            <div className="apb-author-content">
                {/* Selected authors section */}
                <div className="apb-authors-selected-wrapper">
                    <h5>{__('Selected Authors', 'author-profile-blocks')}</h5>
                    <div
                        className="apb-selected-authors-list"
                        role="region"
                        aria-label={__('Selected authors', 'author-profile-blocks')}
                    >
                        {selectedAuthorIds.length > 0 ? (
                            selectedAuthorIds.map(id => {
                                const author = selectedAuthorsMap.get(id);
                                if (!author) return null;

                                console.log('author', author)

                                return (
                                    <SelectedAuthorChip
                                        key={id}
                                        author={author}
                                        onRemove={handleRemoveAuthor}
                                        showAvatar={showAvatars}
                                    />
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
                    <h5 id="apb-search-authors-label">{__('Find Authors', 'author-profile-blocks')}</h5>
                    <SearchControl
                        value={searchTerm}
                        onChange={handleSearchChange}
                        placeholder={__('Search by name...', 'author-profile-blocks')}
                        className="apb-author-search"
                        onKeyDown={handleSearchKeyPress}
                        aria-labelledby="apb-search-authors-label"
                        aria-controls={showDropdown ? "apb-search-results" : undefined}
                        aria-expanded={showDropdown}
                    />

                    {/* Search results dropdown */}
                    {showDropdown && searchTerm && (
                        <div
                            className="apb-search-results"
                            id="apb-search-results"
                            role="listbox"
                            aria-label={__('Author search results', 'author-profile-blocks')}
                        >
                            {availableAuthors.length > 0 ? (
                                <ul>
                                    {availableAuthors.slice(0, 5).map(author => (
                                        <AuthorResultItem
                                            key={author.id}
                                            author={author}
                                            onSelect={handleDirectSelect}
                                            showAvatar={showAvatars}
                                        />
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
                                label={__('Or select from matching authors', 'author-profile-blocks')}
                                value={authorId}
                                options={[
                                    { label: __('-- Select an author --', 'author-profile-blocks'), value: '' },
                                    ...availableAuthors.map(author => ({
                                        label: author.name,
                                        value: author.id
                                    }))
                                ]}
                                onChange={setAuthorId}
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

            {/* Footer with actions */}
            <div className="apb-author-footer">
                <Flex justify="space-between" align="center">
                    <FlexItem>
                        {selectedAuthorIds.length > 0 && (
                            <Button
                                variant="tertiary"
                                onClick={handleClearAll}
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
