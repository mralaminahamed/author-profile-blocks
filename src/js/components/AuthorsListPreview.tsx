/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Author } from '@/js';

/**
 * AuthorsListPreview component for previewing author list in editor
 */
interface AuthorsListPreviewProps {
	authors?: Author[];
	attributes?: {
		displayStyle?: string;
		showImage?: boolean;
		showPosition?: boolean;
		showEmail?: boolean;
		showDescription?: boolean;
		showSocial?: boolean;
		listStyle?: string;
		enableDividers?: boolean;
		textAlign?: string;
		[key: string]: any;
	};
}

const AuthorsListPreview = ({
	authors = [],
	attributes = {},
}: AuthorsListPreviewProps) => {
	if (! authors.length) {
		return (
			<div className="apbl-authors-list-preview apbl-no-authors">
				<p>{ __('No authors to display.', 'author-profile-blocks') }</p>
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

	// No need for ListTag since we'll use conditional rendering
	const classes = [
		'apbl-authors-list-preview',
		`apbl-display-${ displayStyle }`,
		`apbl-text-align-${ textAlign }`,
		enableDividers ? 'apbl-has-dividers' : '',
	]
		.filter(Boolean)
		.join(' ');

	return (
		<div className={ classes }>
			{ listStyle === 'ol' ? (
				<ol className="apbl-authors-list">
					{ authors.map((author, index) => (
						<li
							key={ author.id || index }
							className="apbl-author-list-item"
						>
							<div className="apbl-author-content">
								{ showImage && author.avatar && (
									<div className="apbl-author-avatar">
										<img
											src={ author.avatar }
											alt={
												author.name ||
												author.display_name ||
												''
											}
											width="60"
											height="60"
										/>
									</div>
								) }

								<div className="apbl-author-info">
									<h3 className="apbl-author-name">
										{ author.name ||
											author.display_name ||
											`User ${ author.id }` }
									</h3>

									{ showPosition && author.position && (
										<div className="apbl-author-position">
											{ author.position }
										</div>
									) }

									{ showEmail && author.email && (
										<div className="apbl-author-email">
											<a href={ `mailto:${ author.email }` }>
												{ author.email }
											</a>
										</div>
									) }

									{ showDescription &&
										author.description &&
										displayStyle === 'detailed' && (
										<div className="apbl-author-description">
											{ author.description.length > 150
												? `${ author.description.substring(0, 150) }...`
												: author.description }
										</div>
									) }
								</div>
							</div>

							{ showSocial &&
								author.social &&
								Object.keys(author.social).some(
									(key) => author.social[ key ],
								) && (
								<div className="apbl-author-social">
									{ Object.entries(author.social).map(
										([ network, url ]) =>
											url && (
												<a
													key={ network }
													href={ url as string }
													className={ `apbl-social-${ network }` }
													target="_blank"
													rel="noopener noreferrer"
												>
													{ network }
												</a>
											),
									) }
								</div>
							) }
						</li>
					)) }
				</ol>
			) : (
				<ul className="apbl-authors-list">
					{ authors.map((author, index) => (
						<li
							key={ author.id || index }
							className="apbl-author-list-item"
						>
							<div className="apbl-author-content">
								{ showImage && author.avatar && (
									<div className="apbl-author-avatar">
										<img
											src={ author.avatar }
											alt={
												author.name ||
												author.display_name ||
												''
											}
											width="60"
											height="60"
										/>
									</div>
								) }

								<div className="apbl-author-info">
									<h3 className="apbl-author-name">
										{ author.name ||
											author.display_name ||
											'' }
									</h3>
									{ showPosition && author.position && (
										<p className="apbl-author-position">
											{ author.position }
										</p>
									) }
									{ showEmail && author.email && (
										<p className="apbl-author-email">
											<a href={ `mailto:${ author.email }` }>
												{ author.email }
											</a>
										</p>
									) }
									{ showDescription && author.description && (
										<div
											className="apbl-author-description"
											dangerouslySetInnerHTML={ {
												__html: author.description,
											} }
										/>
									) }
									{ showSocial && author.social && (
										<div className="apbl-author-social">
											{ Object.entries(author.social).map(
												([ network, url ]) => (
													<a
														key={ network }
														href={ url as string }
														target="_blank"
														rel="noopener noreferrer"
														className={ `apbl-social-link apbl-social-${ network }` }
													>
														{ network }
													</a>
												),
											) }
										</div>
									) }
								</div>
							</div>
						</li>
					)) }
				</ul>
			) }
		</div>
	);
};

export default AuthorsListPreview;
