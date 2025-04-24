/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { AuthorSelector as CommonAuthorSelector } from '../../../js/components';

/**
 * AuthorSelector component - wrapper around the common component
 * 
 * @param {Object} props Component props
 * @return {JSX.Element} Component to render
 */
const AuthorSelector = (props) => {
    return (
        <CommonAuthorSelector
            title={__('Author Profile Block', 'author-profile-blocks')}
            instructions={__('Select an author to display their profile in your content.', 'author-profile-blocks')}
            {...props}
        />
    );
};

export default AuthorSelector;
