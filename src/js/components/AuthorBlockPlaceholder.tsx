/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Placeholder, Spinner, Icon } from '@wordpress/components';
import { people } from '@wordpress/icons';

import React from 'react';

/**
 * AuthorBlockPlaceholder component - loading/empty state placeholder
 */
interface AuthorBlockPlaceholderProps {
	title?: string;
	instructions?: string;
	isLoading?: boolean;
	icon?: any;
	selectedAuthorIds?: number[];
	onChange?: (selectedIds: any) => void;
	buttonLabel?: string;
	layoutSelector?: React.ReactNode;
	className?: string;
	additionalControls?: React.ReactNode;
	children?: React.ReactNode;
}

const AuthorBlockPlaceholder = ({
	title = __('Author Block', 'author-profile-blocks'),
	instructions = __(
		'Configure your author block settings.',
		'author-profile-blocks',
	),
	isLoading = false,
	icon: _icon,
	selectedAuthorIds: _selectedAuthorIds,
	onChange: _onChange,
	buttonLabel: _buttonLabel,
	layoutSelector: _layoutSelector,
	className: _className,
	additionalControls: _additionalControls,
	children,
}: AuthorBlockPlaceholderProps) => {
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
					<p>{ __('Loading…', 'author-profile-blocks') }</p>
				</div>
			) : (
				children
			) }
		</Placeholder>
	);
};

export default AuthorBlockPlaceholder;
