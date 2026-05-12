import { __ } from '@wordpress/i18n';
import { Spinner } from '@wordpress/components';
import type { Author } from '../types';

interface Attributes {
	displayStyle?: string;
	showImage?: boolean;
	showPosition?: boolean;
	showEmail?: boolean;
	showDescription?: boolean;
	showSocial?: boolean;
	listStyle?: string;
	enableDividers?: boolean;
	textAlign?: string;
	[ key: string ]: unknown;
}

interface Props {
	authors?: Author[];
	attributes?: Attributes;
	isLoading?: boolean;
	error?: string | null;
}

const AuthorsListPreview = ( { authors = [], attributes = {}, isLoading = false, error = null }: Props ) => {
	if ( isLoading ) {
		return (
			<div className="apbl-author-list-preview apbl-loading">
				<Spinner />
				<p>{ __( 'Loading authors…', 'author-profile-blocks' ) }</p>
			</div>
		);
	}

	if ( error ) {
		return (
			<div className="apbl-author-list-preview apbl-author-list-error">
				{ error }
			</div>
		);
	}

	if ( ! authors.length ) {
		return (
			<div className="apbl-author-list-preview">
				<p>{ __( 'No authors to display.', 'author-profile-blocks' ) }</p>
			</div>
		);
	}

	const {
		displayStyle = 'compact',
		showImage = true,
		showPosition = true,
		showEmail = false,
		showDescription = false,
		listStyle = 'ul',
		enableDividers = true,
		textAlign = 'left',
	} = attributes;

	const ListTag = listStyle as 'ul' | 'ol';

	const listClasses = [
		'apbl-author-list',
		`apbl-display-${ displayStyle }`,
		`apbl-text-align-${ textAlign }`,
		enableDividers ? 'has-dividers' : '',
	].filter( Boolean ).join( ' ' );

	const layoutClass = displayStyle === 'detailed' ? 'apbl-author-detailed' : 'apbl-author-compact';

	return (
		<ListTag className={ listClasses }>
			{ authors.map( ( author, index ) => {
				const avatarSrc = author.avatar_url || author.avatar_urls?.[ '96' ] || author.avatar_urls?.[ '48' ];
				const position = author.author_position;
				const bio = author.author_description || author.description;

				return (
					<li key={ author.id || index } className="apbl-author-list-item">
						<div className="apbl-author-list-item-content">
							<div className={ layoutClass }>
								{ showImage && avatarSrc && (
									<div className="apbl-author-image">
										<img
											src={ avatarSrc }
											alt={ author.name || '' }
											width={ displayStyle === 'detailed' ? 80 : 56 }
											height={ displayStyle === 'detailed' ? 80 : 56 }
											loading="lazy"
										/>
									</div>
								) }

								<div className="apbl-author-info">
									<span className="apbl-author-name">
										{ author.name || `User ${ author.id }` }
									</span>

									{ showPosition && position && (
										<span className="apbl-author-position">{ position }</span>
									) }

									{ showEmail && author.email && (
										<a className="apbl-author-email" href={ `mailto:${ author.email }` }>
											{ author.email }
										</a>
									) }

									{ showDescription && bio && displayStyle === 'detailed' && (
										<p className="apbl-author-description">
											{ bio.length > 120
												? `${ bio.substring( 0, 120 ) }…`
												: bio }
										</p>
									) }
								</div>
							</div>
						</div>
					</li>
				);
			} ) }
		</ListTag>
	);
};

export default AuthorsListPreview;
