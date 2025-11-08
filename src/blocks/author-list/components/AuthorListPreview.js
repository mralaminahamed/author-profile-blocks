/**
 * Internal dependencies
 */
import { AuthorsListPreview } from '../../../js/components';

/**
 * Author list preview component for the editor - Wrapper around the common component.
 *
 * @param {Object} props Component props.
 * @return {JSX.Element} Element to render.
 */
export default function AuthorListPreview( props ) {
	return <AuthorsListPreview { ...props } />;
}
