/**
 * WordPress dependencies
 */
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

/**
 * AuthorCarouselPreview component for showing the carousel of authors in the editor
 *
 * @param {Object} props            Component props
 * @param {Object} props.attributes Block attributes
 * @return {JSX.Element} Component to render
 */
const AuthorCarouselPreview = ( { attributes } ) => {
	// Generate a simple placeholder in the editor instead of trying to initialize Slick
	// The real carousel will be rendered on the frontend
	if ( attributes.authorIds && attributes.authorIds.length > 0 ) {
		const slidesToShow = attributes.slidesToShow || 3;

		return (
			<div className="apbl-carousel-placeholder">
				{ Array.from( {
					length: Math.min( slidesToShow, attributes.authorIds.length ),
				} ).map( ( _, index ) => (
					<div key={ index } className="apbl-carousel-slide-placeholder">
						{ attributes.showImage && (
							<div className="apbl-placeholder-image"></div>
						) }
						<div className="apbl-placeholder-line apb-placeholder-title"></div>
						{ attributes.showPosition && (
							<div className="apbl-placeholder-line"></div>
						) }
						{ attributes.showDescription && (
							<>
								<div className="apbl-placeholder-line apb-placeholder-text"></div>
								<div className="apbl-placeholder-line apb-placeholder-text"></div>
								<div className="apbl-placeholder-line apb-placeholder-text"></div>
							</>
						) }
					</div>
				) ) }
				<div className="apbl-editor-note">
					<em>
						{ __(
							'Carousel preview - will be fully functional on the frontend',
							'author-profile-blocks',
						) }
					</em>
				</div>
			</div>
		);
	}

	return (
		<ServerSideRender
			block="author-profile-blocks/author-carousel"
			attributes={ attributes }
		/>
	);
};

export default AuthorCarouselPreview;
