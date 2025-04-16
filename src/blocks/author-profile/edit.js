/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, BlockControls, AlignmentToolbar } from '@wordpress/block-editor';
import {
	PanelBody,
	ToggleControl,
	Placeholder,
	Spinner,
	TextControl,
	Button
} from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import ServerSideRender from '@wordpress/server-side-render';
import apiFetch from '@wordpress/api-fetch';

/**
 * Editor styles
 */
import './editor.scss';

/**
 * The edit function for the Author Profile block.
 *
 * @param {Object} props Block properties.
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const { authorId, showMoreContent, moreContent, textAlign } = attributes;
	const [isLoading, setIsLoading] = useState(false);
	const [authors, setAuthors] = useState([]);
	const [searchTerm, setSearchTerm] = useState('');
	const [selectedAuthor, setSelectedAuthor] = useState(null);

	const blockProps = useBlockProps({
		className: textAlign ? `has-text-align-${textAlign}` : '',
	});

	// Fetch authors when the component mounts or when the search term changes
	useEffect(() => {
		const fetchAuthors = async () => {
			setIsLoading(true);
			try {
				const response = await apiFetch({
					path: '/wp/v2/author_profile',
					method: 'GET',
				});
				setAuthors(response);

				// Find selected author if we have an authorId
				if (authorId) {
					const selected = response.find(author => author.id === authorId);
					setSelectedAuthor(selected);
				}
			} catch (error) {
				console.error('Error fetching authors:', error);
			} finally {
				setIsLoading(false);
			}
		};

		fetchAuthors();
	}, [authorId]);

	// Filter authors based on search term
	const filteredAuthors = authors.filter(author =>
		author.title.rendered.toLowerCase().includes(searchTerm.toLowerCase())
	);

	// Handle author selection
	const selectAuthor = (author) => {
		setAttributes({ authorId: author.id });
		setSelectedAuthor(author);
		setSearchTerm('');
	};

	return (
		<>
			<BlockControls>
				<AlignmentToolbar
					value={textAlign}
					onChange={(newAlign) => setAttributes({ textAlign: newAlign })}
				/>
			</BlockControls>

			<InspectorControls>
				<PanelBody title={__('Author Profile Settings', 'wp-author-showcase')}>
					<ToggleControl
						label={__('Show More Content Section', 'wp-author-showcase')}
						checked={showMoreContent}
						onChange={() => setAttributes({ showMoreContent: !showMoreContent })}
					/>

					{authorId > 0 && (
						<Button
							isDestructive
							variant="secondary"
							className="wpas-clear-button"
							onClick={() => {
								setAttributes({ authorId: 0 });
								setSelectedAuthor(null);
							}}
						>
							{__('Clear Selected Author', 'wp-author-showcase')}
						</Button>
					)}
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{!authorId ? (
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
											onClick={() => selectAuthor(author)}
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
				) : (
					<>
						<ServerSideRender
							block="wp-author-showcase/author-profile"
							attributes={attributes}
						/>

						{showMoreContent && (
							<div className="wpas-author-more-content">
								<TextControl
									value={moreContent}
									onChange={(value) => setAttributes({ moreContent: value })}
									placeholder={__('Add additional content here...', 'wp-author-showcase')}
									multiline="p"
								/>
							</div>
						)}
					</>
				)}
			</div>
		</>
	);
}
