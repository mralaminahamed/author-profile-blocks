/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Placeholder, Spinner, Icon } from '@wordpress/components';
import { people } from '@wordpress/icons';

/**
 * AuthorBlockPlaceholder component - loading/empty state placeholder
 *
 * @param {Object}      props              Component props
 * @param {string}      props.title        Title for the placeholder
 * @param {string}      props.instructions Instructions text
 * @param {boolean}     props.isLoading    Whether the placeholder is in loading state
 * @param {JSX.Element} props.children     Optional children to render inside placeholder
 * @return {JSX.Element} Component to render
 */
const AuthorBlockPlaceholder = ( {
	title = __( 'Author Block', 'author-profile-blocks' ),
	instructions = __(
		'Configure your author block settings.',
		'author-profile-blocks',
	),
	isLoading = false,
	children,
} ) => {
	return (
		<Placeholder
			icon={ <Icon icon={ people } /> }
			label={ title }
			instructions={ instructions }
			className="apbl-block-placeholder"
		>
			{ isLoading ? (
				<div className="apbl-placeholder-loading">
					<Spinner />
					<p>{ __( 'Loading…', 'author-profile-blocks' ) }</p>
				</div>
			) : (
				children
			) }
		</Placeholder>
	);
};

export default AuthorBlockPlaceholder;
