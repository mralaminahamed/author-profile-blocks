/**
 * WordPress dependencies
 */
import ServerSideRender from '@wordpress/server-side-render';

/**
 * AuthorGridPreview component for showing the grid of authors in the editor
 *
 * @param {Object} props            Component props
 * @param {Object} props.attributes Block attributes
 * @return {JSX.Element} Component to render
 */
const AuthorGridPreview = ({ attributes }) => {
	return (
		<ServerSideRender
			block="author-profile-blocks/author-grid"
			attributes={attributes}
		/>
	);
};

export default AuthorGridPreview;
