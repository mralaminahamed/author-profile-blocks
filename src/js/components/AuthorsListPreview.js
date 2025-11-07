/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * AuthorsListPreview component for previewing author list in editor
 *
 * @param {Object} props            Component props
 * @param {Array}  props.authors    Array of author objects
 * @param {Object} props.attributes Block attributes
 * @return {JSX.Element} Component to render
 */
const AuthorsListPreview = ({ authors = [], attributes = {} }) => {
	if (!authors.length) {
		return (
			<div className="apbl-authors-list-preview apbl-no-authors">
				<p>{__('No authors to display.', 'author-profile-blocks')}</p>
			</div>
		);
	}

	const {
		displayStyle = 'compact',
		showImage = true,
		showPosition = true,
		showEmail = false,
		showDescription = false,
		showSocial = true,
		listStyle = 'ul',
		enableDividers = true,
		textAlign = 'left',
	} = attributes;

	const ListTag = listStyle;
	const classes = [
		'apbl-authors-list-preview',
		`apbl-display-${displayStyle}`,
		`apbl-text-align-${textAlign}`,
		enableDividers ? 'apbl-has-dividers' : '',
	]
		.filter(Boolean)
		.join(' ');

	return (
		<div className={classes}>
			<ListTag className="apbl-authors-list">
				{authors.map((author, index) => (
					<li
						key={author.id || index}
						className="apbl-author-list-item"
					>
						<div className="apbl-author-content">
							{showImage && author.avatar && (
								<div className="apbl-author-avatar">
									<img
										src={author.avatar}
										alt={
											author.name ||
											author.display_name ||
											''
										}
										width="60"
										height="60"
									/>
								</div>
							)}

							<div className="apbl-author-info">
								<h3 className="apbl-author-name">
									{author.name ||
										author.display_name ||
										`User ${author.id}`}
								</h3>

								{showPosition && author.position && (
									<div className="apbl-author-position">
										{author.position}
									</div>
								)}

								{showEmail && author.email && (
									<div className="apbl-author-email">
										<a href={`mailto:${author.email}`}>
											{author.email}
										</a>
									</div>
								)}

								{showDescription &&
									author.description &&
									displayStyle === 'detailed' && (
									<div className="apbl-author-description">
											{author.description.length > 150
											? `${author.description.substring(0, 150)}...`
												: author.description}
									</div>
								)}
							</div>
						</div>

						{showSocial &&
							author.social &&
							Object.keys(author.social).some(
								(key) => author.social[key]
							) && (
							<div className="apbl-author-social">
									{Object.entries(author.social).map(
										([network, url]) =>
										url && (
												<a
													key={network}
													href={url}
													className={`apbl-social-${network}`}
													target="_blank"
												rel="noopener noreferrer"
												>
												{network}
											</a>
											)
								)}
							</div>
						)}
					</li>
				))}
			</ListTag>
		</div>
	);
};

export default AuthorsListPreview;
