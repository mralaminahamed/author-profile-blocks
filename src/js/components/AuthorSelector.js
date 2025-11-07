/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	Placeholder,
	Spinner,
	SelectControl,
	Button,
	TextControl,
	Card,
	CardHeader,
	CardBody,
	CardFooter,
	Icon,
	Notice,
	Flex,
	FlexItem,
	Dashicon,
	Tooltip
} from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import { search, people, plusCircle, info } from '@wordpress/icons';
import { sprintf } from '@wordpress/i18n';

/**
 * AuthorSelector component for selecting an author from a dropdown with search
 *
 * @param {Object}         props                     Component props
 * @param {Array}          props.authors             Array of author objects
 * @param {Function}       props.onSelectAuthor      Callback for when an author is selected
 * @param {boolean}        props.isLoading           Whether authors are currently loading
 * @param {string}         props.title               Title for the component
 * @param {string}         props.instructions        Instructions text
 * @param {string|Element} props.placeholderIcon     Icon to use in the placeholder, defaults to people
 * @param {boolean}        props.showAddAuthorButton Whether to show the "Add New User" button
 * @return {JSX.Element} Component to render
 */
const AuthorSelector = ({
	authors = [], 
	onSelectAuthor, 
	isLoading = false,
	title = __('Author Selection', 'author-profile-blocks'),
	instructions = __('Select an author to display in your content.', 'author-profile-blocks'),
	placeholderIcon = people,
	showAddAuthorButton = true
}) => {
	const [selectedAuthorId, setSelectedAuthorId] = useState('');
	const [searchTerm, setSearchTerm] = useState('');
	const [authorCount, setAuthorCount] = useState(0);

	// Update author count when authors array changes
	useEffect(() => {
		setAuthorCount(authors.length);
	}, [authors]);

	// Filter authors based on search term
	const filteredAuthors = authors.filter(author =>
		author.name.toLowerCase().includes(searchTerm.toLowerCase())
	);

	// Prepare options for select control
	const authorOptions = [
		{ label: __('Select an author…', 'author-profile-blocks'), value: '' },
		...filteredAuthors.map(author => ({
			label: author.name,
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

	// Reset search term
	const clearSearch = () => {
		setSearchTerm('');
	};

	return (
		<div className="apbl-author-selector-wrapper">
			<Placeholder
				icon={<Icon icon={placeholderIcon} className="apbl-author-icon" />}
				label={title}
				instructions={instructions}
				className="apbl-author-selector"
				isColumnLayout={true}
			>
				{isLoading ? (
					<div className="apbl-loading-container">
						<Spinner />
						<p>{__('Loading authors…', 'author-profile-blocks')}</p>
					</div>
				) : authors.length > 0 ? (
					<Card className="apbl-author-card" elevation={2}>
						<CardHeader className="apbl-card-header">
							<Flex justify="space-between" align="center">
								<FlexItem>
									<Flex align="center" gap={2}>
										<Icon icon={people} size={24} />
										<h4>{__('Author Selection', 'author-profile-blocks')}</h4>
									</Flex>
								</FlexItem>
								<FlexItem>
									<div className="apbl-author-count">
										<span>{authorCount}</span>
										<Tooltip text={__('Total number of available authors', 'author-profile-blocks')}>
											<Icon icon={info} size={16} />
										</Tooltip>
									</div>
								</FlexItem>
							</Flex>
						</CardHeader>

						<CardBody>
							<div className="apbl-search-field">
								<Icon icon={search} className="apbl-search-icon" />
								<TextControl
									value={searchTerm}
									onChange={handleSearchChange}
									placeholder={__('Search authors…', 'author-profile-blocks')}
									className="apbl-author-search"
								/>
								{searchTerm && (
									<Button
										className="apbl-clear-search"
										isSmall
										isSecondary
										onClick={clearSearch}
										aria-label={__('Clear search', 'author-profile-blocks')}
									>
										<Dashicon icon="no-alt" />
									</Button>
								)}
							</div>

							<div className="apbl-select-field">
								<SelectControl
									label={__('Select Author', 'author-profile-blocks')}
									value={selectedAuthorId}
									options={authorOptions}
									onChange={handleAuthorChange}
									className="apbl-author-select"
									__nextHasNoMarginBottom
								/>
							</div>

							{filteredAuthors.length === 0 && searchTerm !== '' ? (
								<Notice
									className="apbl-notice"
									status="warning"
									isDismissible={false}
								>
									{__('No authors match your search criteria.', 'author-profile-blocks')}
								</Notice>
							) : filteredAuthors.length < authors.length && searchTerm !== '' && (
									<div className="apbl-filter-info">
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
								)
							)}
						</CardBody>

						<CardFooter className="apbl-card-footer">
							<Flex justify="space-between" align="center">
								<FlexItem>
									{selectedAuthorId && (
										<Button
											variant="tertiary"
											onClick={() => {
												setSelectedAuthorId('');
												setSearchTerm('');
											}}
											className="apbl-reset-btn"
										>
											{__('Reset', 'author-profile-blocks')}
										</Button>
									)}
								</FlexItem>
							</Flex>
						</CardFooter>
					</Card>
				) : (
					<div className="apbl-no-results">
						<Card className="apbl-empty-state-card" elevation={2}>
							<CardBody>
								<div className="apbl-empty-state">
									<Icon icon={people} size={48} className="apbl-empty-icon" />
									<h3>{__('No Users Found', 'author-profile-blocks')}</h3>
									<p>{__('You need to create users with appropriate roles before you can use this block.', 'author-profile-blocks')}</p>
									{showAddAuthorButton && (
										<Button
											variant="primary"
											href={`${window.AuthorProfileBlocks?.adminUrl || '/wp-admin/'}user-new.php`}
											target="_blank"
											className="apbl-create-author-btn"
											icon={plusCircle}
										>
											{__('Create New User', 'author-profile-blocks')}
										</Button>
									)}
								</div>
							</CardBody>
						</Card>
					</div>
				)}
			</Placeholder>
		</div>
	);
};

export default AuthorSelector;
