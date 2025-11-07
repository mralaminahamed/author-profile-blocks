/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button, SelectControl, Spinner } from '@wordpress/components';
import { useState } from '@wordpress/element';

/**
 * Multi-author selector component for selecting multiple authors
 *
 * @param {Object}   props                 Component props
 * @param {Array}    props.authors         List of available authors
 * @param {Array}    props.selectedIds     List of selected author IDs
 * @param {Function} props.onSelectAuthors Callback when authors are selected
 * @param {boolean}  props.isLoading       Loading state
 * @param {string}   props.title           Title for the selector (e.g., "Select Authors for Grid")
 * @param {string}   props.buttonText      Text for the add button (e.g., "Add Author to Grid")
 * @return {JSX.Element} Component to render
 */
const MultiAuthorSelector = ({
	authors,
	selectedIds = [],
	onSelectAuthors,
	isLoading = false,
	title = __('Select Authors', 'author-profile-blocks'),
	buttonText = __('Add Author', 'author-profile-blocks'),
}) => {
	const [authorId, setAuthorId] = useState('');

	// Filter out already selected authors
	const availableAuthors = authors.filter(
		(author) => !selectedIds.includes(author.id)
	);

	// Prepare options for select control
	const authorOptions = [
		{ label: __('Select an author…', 'author-profile-blocks'), value: '' },
		...availableAuthors.map((author) => ({
			label: author.name,
			value: author.id.toString(),
		})),
	];

	// Handle adding a new author
	const handleAddAuthor = () => {
		if (authorId) {
			const newSelectedIds = [...selectedIds, parseInt(authorId)];
			onSelectAuthors(newSelectedIds);
			setAuthorId('');
		}
	};

	// Handle removing an author
	const handleRemoveAuthor = (authorIdToRemove) => {
		const newSelectedIds = selectedIds.filter(
			(id) => id !== authorIdToRemove
		);
		onSelectAuthors(newSelectedIds);
	};

	// Get selected authors data for display
	const selectedAuthors = selectedIds
		.map((id) => authors.find((author) => author.id === id))
		.filter(Boolean);

	if (isLoading) {
		return (
			<div className="apbl-authors-selector">
				<Spinner />
				<p>{__('Loading authors…', 'author-profile-blocks')}</p>
			</div>
		);
	}

	return (
		<div className="apbl-authors-selector">
			<h3>{title}</h3>

			{authors.length === 0 ? (
				<p>{__('No authors available.', 'author-profile-blocks')}</p>
			) : (
				<>
					<div className="apbl-author-select-controls">
						<SelectControl
							value={authorId}
							options={authorOptions}
							onChange={setAuthorId}
							disabled={availableAuthors.length === 0}
						/>
						<Button
							variant="secondary"
							onClick={handleAddAuthor}
							disabled={
								!authorId || availableAuthors.length === 0
							}
						>
							{buttonText}
						</Button>
					</div>

					{selectedAuthors.length > 0 && (
						<div className="apbl-selected-authors">
							<h4>
								{__(
									'Selected Authors:',
									'author-profile-blocks'
								)}
							</h4>
							<ul className="apbl-selected-authors-list">
								{selectedAuthors.map((author) => (
									<li
										key={author.id}
										className="apbl-selected-author-item"
									>
										<span className="apbl-author-name">
											{author.name}
										</span>
										<Button
											variant="link"
											isDestructive
											onClick={() =>
												handleRemoveAuthor(author.id)
											}
											className="apbl-remove-author"
										>
											{__(
												'Remove',
												'author-profile-blocks'
											)}
										</Button>
									</li>
								))}
							</ul>
						</div>
					)}

					{availableAuthors.length === 0 &&
						selectedAuthors.length > 0 && (
						<p className="apbl-all-selected">
								{__(
									'All available authors have been selected.',
									'author-profile-blocks'
								)}
						</p>
					)}
				</>
			)}
		</div>
	);
};

export default MultiAuthorSelector;
