/**
 * WordPress dependencies
 */
import ServerSideRender from '@wordpress/server-side-render';

/**
 * AuthorPreview component for showing the selected author
 *
 * @param {Object} props             Component props
 * @param {Object} props.attributes  Block attributes
 * @return {JSX.Element} Component to render
 */
const AuthorPreview = ({ attributes }) => {
    return (
        <ServerSideRender
            block="author-profile-blocks/author-profile"
            attributes={attributes}
        />
    );
};

export default AuthorPreview;
