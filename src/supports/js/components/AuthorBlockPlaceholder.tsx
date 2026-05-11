/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	Placeholder,
	Spinner,
	Icon,
	SelectControl,
	Button,
	Flex,
	FlexItem,
} from '@wordpress/components';
import { useState } from '@wordpress/element';
import { people } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import useAuthors from '../hooks/useAuthors';

/**
 * Placeholder shown inside the editor when an Author Profile / Grid / List /
 * Carousel block has no author(s) selected yet. Renders a search-friendly
 * <SelectControl> populated from the REST API plus an "Add" button so the
 * user can actually choose an author from inside the canvas.
 *
 * Props match the call sites in src/blocks/{author-*}/edit.js.
 *
 * @param {Object}      props
 * @param {string}      props.title             Heading text.
 * @param {string}      props.instructions      Helper text under the heading.
 * @param {boolean}     props.isLoading         External loading state (rare; usually unused).
 * @param {string}      props.icon              Dashicons slug or icon component for the placeholder header.
 * @param {number[]}    props.selectedAuthorIds IDs already saved to the block attributes.
 * @param {Function}    props.onChange          Receives a fresh array of IDs when the user picks/removes one.
 * @param {string}      props.buttonLabel       Label of the "Add" button.
 * @param {boolean}     props.single            When true, replaces the existing selection instead of appending.
 * @param {JSX.Element} props.layoutSelector    Optional render slot below the picker (used by Grid).
 * @param {JSX.Element} props.additionalControls Optional render slot.
 * @param {JSX.Element} props.children          Backwards-compatible child content (rendered when no picker props are provided).
 *
 * @return {JSX.Element} Component to render.
 */
const AuthorBlockPlaceholder = ( {
	title = __( 'Author Block', 'author-profile-blocks' ),
	instructions = __( 'Configure your author block settings.', 'author-profile-blocks' ),
	isLoading = false,
	icon,
	selectedAuthorIds,
	onChange,
	buttonLabel = __( 'Add Author', 'author-profile-blocks' ),
	single = false,
	layoutSelector,
	additionalControls,
	className,
	children,
}: {
	title?: string;
	instructions?: string;
	isLoading?: boolean;
	icon?: string | JSX.Element;
	selectedAuthorIds?: number[];
	onChange?: ( ids: number[] ) => void;
	buttonLabel?: string;
	single?: boolean;
	layoutSelector?: JSX.Element;
	additionalControls?: JSX.Element;
	className?: string;
	children?: React.ReactNode;
} ) => {
	const hasPicker = typeof onChange === 'function';
	const ids = Array.isArray( selectedAuthorIds ) ? selectedAuthorIds : [];

	const { authors, isLoading: isLoadingAuthors } = useAuthors( 0 );

	const [ pendingId, setPendingId ] = useState( '' );

	const placeholderIcon =
		typeof icon === 'string' ? icon : icon ?? people;

	const renderPicker = () => {
		const loading = isLoading || isLoadingAuthors;

		if ( loading ) {
			return (
				<div className="apbl-placeholder-loading">
					<Spinner />
					<p>{ __( 'Loading authors…', 'author-profile-blocks' ) }</p>
				</div>
			);
		}

		if ( ! authors.length ) {
			return (
				<div className="apbl-placeholder-empty">
					<p>
						{ __(
							'No authors available. Create a user with an author role first.',
							'author-profile-blocks',
						) }
					</p>
					<Button
						variant="primary"
						href={ `${ ( window as any ).apblAdmin?.adminUrl || '/wp-admin/' }user-new.php` }
						target="_blank"
					>
						{ __( 'Create New User', 'author-profile-blocks' ) }
					</Button>
				</div>
			);
		}

		const availableAuthors = single
			? authors
			: authors.filter( ( author ) => ! ids.includes( author.id ) );

		const options = [
			{
				label: __( 'Select an author…', 'author-profile-blocks' ),
				value: '',
			},
			...availableAuthors.map( ( author ) => ( {
				label:
					author.name ||
					author.display_name ||
					`User ${ author.id }`,
				value: author.id.toString(),
			} ) ),
		];

		const handleAdd = () => {
			if ( ! pendingId ) {
				return;
			}
			const newId = parseInt( pendingId, 10 );
			if ( Number.isNaN( newId ) ) {
				return;
			}
			const nextIds = single ? [ newId ] : [ ...ids, newId ];
			onChange( nextIds );
			setPendingId( '' );
		};

		return (
			<div className="apbl-placeholder-picker">
				<Flex
					className="apbl-placeholder-picker-controls"
					align="center"
					gap={ 2 }
				>
					<FlexItem isBlock>
						<SelectControl
							label={ __( 'Author', 'author-profile-blocks' ) }
							hideLabelFromVision
							value={ pendingId }
							options={ options }
							onChange={ setPendingId }
						/>
					</FlexItem>
					<FlexItem>
						<Button
							variant="primary"
							onClick={ handleAdd }
							disabled={ ! pendingId }
						>
							{ buttonLabel }
						</Button>
					</FlexItem>
				</Flex>

				{ ! single && ids.length > 0 && (
					<p className="apbl-placeholder-count">
						{ __( 'Selected:', 'author-profile-blocks' ) } { ids.length }
					</p>
				) }

				{ layoutSelector }
				{ additionalControls }
			</div>
		);
	};

	return (
		<Placeholder
			icon={ <Icon icon={ placeholderIcon } /> }
			label={ title }
			instructions={ instructions }
			className={ [ 'apbl-block-placeholder', className ].filter( Boolean ).join( ' ' ) }
			isColumnLayout
		>
			{ hasPicker ? renderPicker() : children }
		</Placeholder>
	);
};

export default AuthorBlockPlaceholder;
