/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import {InspectorControls, RichText, useBlockProps} from '@wordpress/block-editor';
import {
	Button,
	ColorPicker,
	PanelBody,
	Placeholder,
	RangeControl,
	SearchControl,
	SelectControl,
	Spinner,
	ToggleControl
} from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @param {Object} props               Block props.
 * @param {Object} props.attributes    Block attributes.
 * @param {Function} props.setAttributes Function to set block attributes.
 * @return {WPElement} Element to render.
 */
export default function Edit({attributes, setAttributes}) {
	const {
		authorId,
		showImage,
		showEmail,
		showDescription,
		showMore,
		moreContent,
		backgroundColor,
		textAlign,
		padding
	} = attributes;

	const [authors, setAuthors] = useState([]);
	const [isLoading, setIsLoading] = useState(false);
	const [selectedAuthor, setSelectedAuthor] = useState(null);
	const [searchTerm, setSearchTerm] = useState('');
	const [isSearching, setIsSearching] = useState(false);

	// Fetch list of author profiles
	useEffect(() => {
		if (authorId) return;

		setIsLoading(true);
		apiFetch({path: '/wp/v2/author_profile?per_page=100'})
			.then((posts) => {
				setAuthors(posts.map(post => ({
					id: post.id,
					name: post.title?.rendered,
					value: post.id
				})));
				setIsLoading(false);
			})
			.catch((error) => {
				console.error('Error fetching author profiles:', error);
				setIsLoading(false);
			});
	}, []);

	// Fetch selected author data when authorId changes
	useEffect(() => {
		if (!authorId) return;

		setIsLoading(true);
		apiFetch({path: `/wp/v2/author_profile/${authorId}`})
			.then((post) => {
				if (!post || !post.title) {
					// Handle case where author profile exists but has incomplete data
					setIsLoading(false);
					console.error('Author profile exists but has incomplete data');
					return;
				}

				setSelectedAuthor({
					name: post.title?.rendered || __('Unnamed Author', 'wp-author-showcase'),
					email: post.meta?.wpas_author_email || '',
					description: post.meta?.wpas_author_description || '',
					featuredImage: post.featured_media && post._links && post._links['wp:featuredmedia']
						? post._links['wp:featuredmedia'][0].href
						: null
				});

				// If there's a featured image, fetch it
				if (post.featured_media && post._links && post._links['wp:featuredmedia']) {
					apiFetch({url: post._links['wp:featuredmedia'][0].href})
						.then((media) => {
							if (!media || !media.source_url) {
								// Handle case where media exists but URL is missing
								setIsLoading(false);
								return;
							}

							setSelectedAuthor(prev => ({
								...prev,
								imageUrl: media.source_url
							}));
							setIsLoading(false);
						})
						.catch((error) => {
							console.error('Error fetching author image:', error);
							// Continue without the image
							setIsLoading(false);
						});
				} else {
					setIsLoading(false);
				}
			})
			.catch((error) => {
				// Handle case where author profile doesn't exist or can't be accessed
				console.error('Error fetching author data:', error);
				setIsLoading(false);

				// If it's a 404 error (author not found), clear the author selection
				if (error.code === 'rest_post_invalid_id') {
					setAttributes({authorId: 0});
				}
			});
	}, [authorId]);

	// Search for authors when searchTerm changes
	useEffect(() => {
		if (searchTerm.length <= 2) return;

		// For 3+ character searches, use the API
		setIsSearching(true);
		apiFetch({path: `/wp/v2/author_profile?search=${encodeURIComponent(searchTerm)}`})
			.then((posts) => {
				setAuthors(posts.map(post => ({
					id: post.id,
					name: post.title?.rendered,
					value: post.id
				})));
				setIsSearching(false);
			})
			.catch((error) => {
				console.error('Error searching author profiles:', error);
				setIsSearching(false);
			});
	}, [searchTerm]);

	// Block wrapper styles
	const blockStyle = {
		backgroundColor,
		textAlign,
		padding: `${padding}px`,
		borderRadius: '4px',
	};

	const blockProps = useBlockProps({
		style: blockStyle
	});

	// Filter authors based on search term for the dropdown
	const filteredAuthors = authors.filter(author =>
		!searchTerm.length ||
		author.name?.toLowerCase().includes(searchTerm.toLowerCase())
	);

	const authorOptions = authors.map(author => ({
		label: author.name,
		value: author.id
	}));

	// If no author is selected or still loading data
	if (!authorId || isLoading) {
		return (
			<div {...blockProps}>
				<Placeholder
					icon="admin-users"
					label={__('Author Profile', 'wp-author-showcase')}
					instructions={__('Search and select an author profile to display.', 'wp-author-showcase')}
				>
					{isLoading ? (
						<Spinner/>
					) : (
						<div className="wpas-author-selector">
							<SearchControl
								value={searchTerm}
								onChange={setSearchTerm}
								label={__('Search authors', 'wp-author-showcase')}
								placeholder={__('Type to search authors...', 'wp-author-showcase')}
							/>

							{isSearching ? (
								<Spinner/>
							) : (
								<SelectControl
									label={__('Select Author', 'wp-author-showcase')}
									value={authorId}
									options={[
										{label: __('-- Select an author --', 'wp-author-showcase'), value: 0},
										...authorOptions
									]}
									onChange={(value) => setAttributes({authorId: parseInt(value ?? '', 10)})}
								/>
							)}

							{searchTerm.length > 0 && authorOptions.length === 0 && !isSearching && (
								<p className="wpas-no-results">{__('No authors found matching your search.', 'wp-author-showcase')}</p>
							)}
						</div>
					)}
				</Placeholder>
			</div>
		);
	}

	// Return the block editor UI with selected author data
	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Author Selection', 'wp-author-showcase')}>
					<SearchControl
						value={searchTerm}
						onChange={setSearchTerm}
						label={__('Search authors', 'wp-author-showcase')}
						placeholder={__('Type to search authors...', 'wp-author-showcase')}
					/>

					{isSearching ? (
						<Spinner/>
					) : (
						<SelectControl
							label={__('Select Author', 'wp-author-showcase')}
							value={authorId}
							options={[
								{label: __('-- Select an author --', 'wp-author-showcase'), value: 0},
								...authorOptions
							]}
							onChange={(value) => setAttributes({authorId: parseInt(value, 10)})}
						/>
					)}

					{searchTerm.length > 0 && authorOptions.length === 0 && !isSearching && (
						<p>{__('No authors found matching your search.', 'wp-author-showcase')}</p>
					)}

					<Button
						isSecondary
						onClick={() => setAttributes({authorId: 0})}
						className="wpas-clear-button"
					>
						{__('Clear Selection', 'wp-author-showcase')}
					</Button>
				</PanelBody>

				<PanelBody title={__('Display Settings', 'wp-author-showcase')}>
					<ToggleControl
						label={__('Show Image', 'wp-author-showcase')}
						checked={showImage}
						onChange={() => setAttributes({showImage: !showImage})}
					/>

					<ToggleControl
						label={__('Show Email', 'wp-author-showcase')}
						checked={showEmail}
						onChange={() => setAttributes({showEmail: !showEmail})}
					/>

					<ToggleControl
						label={__('Show Description', 'wp-author-showcase')}
						checked={showDescription}
						onChange={() => setAttributes({showDescription: !showDescription})}
					/>

					<ToggleControl
						label={__('Show More Section', 'wp-author-showcase')}
						checked={showMore}
						onChange={() => setAttributes({showMore: !showMore})}
						help={__('Add a custom content section below the author profile.', 'wp-author-showcase')}
					/>
				</PanelBody>

				<PanelBody title={__('Style Settings', 'wp-author-showcase')}>
					<div className="wpas-color-setting">
						<p>{__('Background Color', 'wp-author-showcase')}</p>
						<ColorPicker
							color={backgroundColor}
							onChange={(color) => setAttributes({backgroundColor: color})}
							enableAlpha
						/>
					</div>

					<SelectControl
						label={__('Text Alignment', 'wp-author-showcase')}
						value={textAlign}
						options={[
							{label: __('Left', 'wp-author-showcase'), value: 'left'},
							{label: __('Center', 'wp-author-showcase'), value: 'center'},
							{label: __('Right', 'wp-author-showcase'), value: 'right'}
						]}
						onChange={(value) => setAttributes({textAlign: value})}
					/>

					<RangeControl
						label={__('Padding (px)', 'wp-author-showcase')}
						value={padding}
						onChange={(value) => setAttributes({padding: value})}
						min={0}
						max={100}
						step={1}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="wpas-author-profile-content" role="region" aria-label={__('Author profile information', 'wp-author-showcase')}>
					{showImage && selectedAuthor?.imageUrl && (
						<div className="wpas-author-image">
							<img
								src={selectedAuthor.imageUrl}
								alt={selectedAuthor.name}
								aria-hidden="false"
							/>
						</div>
					)}

					<div className="wpas-author-info">
						<h3 className="wpas-author-name">
							{selectedAuthor?.name}
						</h3>

						{showEmail && selectedAuthor?.email && (
							<div className="wpas-author-email">
								<a
									href={`mailto:${selectedAuthor.email}`}
									aria-label={__('Email', 'wp-author-showcase') + ': ' + selectedAuthor.email}
								>
									{selectedAuthor.email}
								</a>
							</div>
						)}

						{showDescription && selectedAuthor?.description && (
							<div
								className="wpas-author-description"
								dangerouslySetInnerHTML={{__html: selectedAuthor.description}}
								aria-label={__('Author description', 'wp-author-showcase')}
							/>
						)}
					</div>
				</div>

				{showMore && (
					<div
						className="wpas-author-more-content"
						aria-label={__('Additional content', 'wp-author-showcase')}
					>
						<RichText
							tagName="div"
							value={moreContent}
							onChange={(content) => setAttributes({moreContent: content})}
							placeholder={__('Add additional content here...', 'wp-author-showcase')}
							aria-label={__('Additional content editor', 'wp-author-showcase')}
						/>
					</div>
				)}
			</div>
		</>
	);
}
