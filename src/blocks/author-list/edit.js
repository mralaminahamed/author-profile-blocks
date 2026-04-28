/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	BlockControls,
	AlignmentToolbar,
} from '@wordpress/block-editor';
import {
	ToolbarGroup,
	ToolbarButton,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { createBlock } from '@wordpress/blocks';
import { list, grid, update } from '@wordpress/icons';
import { useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './editor.scss';
import { AuthorBlockPlaceholder } from '../../supports/js/components';
import AuthorListPreview from './components/AuthorListPreview';
import useAuthors from './hooks/useAuthors';
import { ContentPanel, StylePanel, LayoutPanel, AdvancedPanel } from './components/inspector';

/**
 * Edit function for the Author List block.
 *
 * @param {Object} props               Block properties.
 * @param          props.attributes
 * @param          props.setAttributes
 * @param          props.clientId
 * @param          props.insertBlocks
 * @return {JSX.Element} Element to render.
 */
export default function Edit( {
	attributes,
	setAttributes,
	clientId,
	insertBlocks,
} ) {
	const {
		authorIds,
		authorRole,
		maxAuthors,
		displayStyle,
		listStyle,
		enableDividers,
		dividerColor,
		enableRounded,
		enableHoverEffect,
		backgroundColor,
		itemBackgroundColor,
		textAlign,
		blockPadding,
		itemPadding,
		itemSpacing,
		showImage,
		showPosition,
		showEmail,
		showDescription,
		showSocial,
		// New advanced attributes
		layoutPreset,
		animationType,
		animationDuration,
		hoverEffect,
		customCssClass,
		googleFont,
		fontSizeUnit,
		gradientBackground,
		gradientStartColor,
		gradientEndColor,
		gradientDirection,
		transformScale,
		transformRotate,
		filterBrightness,
		filterContrast,
		filterSaturate,
		lazyLoad,
		contentTabs,
		tabLabels,
		margin,
		sectionSpacing,
		boxShadow,
		boxShadowColor,
		boxShadowBlur,
		boxShadowSpread,
		boxShadowHorizontal,
		boxShadowVertical,
		borderWidth,
		borderColor,
		borderRadius,
		containerWidth,
		customVar1,
		customVar2,
	} = attributes;

	// Use our custom hook to fetch and manage authors
	const { authors, isLoading, error } = useAuthors(
		authorIds,
		authorRole,
		maxAuthors,
	);

	// Get roles for dropdown
	const authorRoles = useSelect( ( select ) => {
		const { getRoles } = select( 'core' );
		return getRoles ? getRoles() : [];
	}, [] );

	// Format roles for dropdown
	const roleOptions = [
		{ label: __( 'All Roles', 'author-profile-blocks' ), value: '' },
	];

	if ( authorRoles && authorRoles.length ) {
		authorRoles.forEach( ( role ) => {
			roleOptions.push( { label: role.name, value: role.id } );
		} );
	}

	// Load Google Font if selected
	useEffect( () => {
		if ( googleFont && googleFont !== '' ) {
			loadGoogleFont( googleFont );
		}
	}, [ googleFont ] );

	const loadGoogleFont = ( fontName ) => {
		if ( ! fontName ) {
			return;
		}

		// Remove existing Google Fonts link if present
		const existingLink = document.querySelector( 'link[href*="fonts.googleapis.com"]' );
		if ( existingLink ) {
			existingLink.remove();
		}

		// Add new Google Font
		const link = document.createElement( 'link' );
		link.href = `https://fonts.googleapis.com/css2?family=${ encodeURIComponent( fontName ) }:wght@300;400;500;600;700&display=swap`;
		link.rel = 'stylesheet';
		document.head.appendChild( link );
	};

	// Handle author IDs change
	const handleAuthorIdsChange = ( newAuthorIds ) => {
		setAttributes( { authorIds: newAuthorIds } );
	};

	// Convert to Grid block
	const convertToGrid = () => {
		const gridBlock = createBlock( 'author-profile-blocks/author-grid', {
			authorIds,
			authorRole,
			maxAuthors,
			columns: 3,
			layout: displayStyle === 'detailed' ? 'card' : 'compact',
			backgroundColor,
			textAlign,
			showImage,
			showPosition,
			showEmail,
			showDescription,
			showSocial,
		} );

		insertBlocks( gridBlock, undefined, clientId, true );
	};

	const getShadowStyle = () => {
		if ( ! boxShadow ) return undefined;
		return `${ boxShadowHorizontal || 0 }px ${ boxShadowVertical || 4 }px ${ boxShadowBlur || 8 }px ${ boxShadowSpread || 0 }px ${ boxShadowColor || 'rgba(0,0,0,0.2)' }`;
	};

	const blockStyles = {
		backgroundColor: backgroundColor || undefined,
		background: gradientBackground
			? `linear-gradient(${ gradientDirection || 'to bottom' }, ${ gradientStartColor || '#ffffff' }, ${ gradientEndColor || '#000000' })`
			: undefined,
		padding: blockPadding ? `${ blockPadding }px` : undefined,
		margin: margin || undefined,
		width: containerWidth || undefined,
		boxShadow: boxShadow ? getShadowStyle() : undefined,
		borderWidth: borderWidth ? `${ borderWidth }px` : undefined,
		borderStyle: borderWidth ? 'solid' : undefined,
		borderColor: borderWidth ? borderColor : undefined,
		borderRadius: enableRounded && borderRadius ? `${ borderRadius }px` : undefined,
		transform: ( transformScale && transformScale !== 1 ) || transformRotate
			? `scale(${ transformScale || 1 }) rotate(${ transformRotate || 0 }deg)`
			: undefined,
		filter: ( filterBrightness && filterBrightness !== 100 ) ||
			( filterContrast && filterContrast !== 100 ) ||
			( filterSaturate && filterSaturate !== 100 )
			? `brightness(${ filterBrightness || 100 }%) contrast(${ filterContrast || 100 }%) saturate(${ filterSaturate || 100 }%)`
			: undefined,
		'--author-list-item-padding': itemPadding ? `${ itemPadding }px` : undefined,
		'--author-list-item-spacing': itemSpacing ? `${ itemSpacing }px` : undefined,
		'--author-list-item-bg': itemBackgroundColor || undefined,
		'--author-list-divider-color': dividerColor || undefined,
		'--author-list-section-spacing': sectionSpacing ? `${ sectionSpacing }px` : undefined,
		'--author-list-container-width': containerWidth || undefined,
		'--author-list-custom-var-1': customVar1 || undefined,
		'--author-list-custom-var-2': customVar2 || undefined,
	};

	// Block props with enhanced styling
	const blockProps = useBlockProps( {
		className: [
			textAlign ? `has-text-align-${ textAlign }` : '',
			layoutPreset ? layoutPreset : '',
			animationType && animationType !== 'none' ? `has-${ animationType }-animation` : '',
			hoverEffect && hoverEffect !== 'none' ? `has-${ hoverEffect }-hover` : '',
			enableDividers ? 'has-dividers' : '',
			enableHoverEffect ? 'has-hover-effect' : '',
			enableRounded ? 'has-rounded' : '',
			customCssClass ? customCssClass : '',
			googleFont ? `has-${ googleFont.toLowerCase().replace( /\s+/g, '-' ) }-font` : '',
		].filter( Boolean ).join( ' ' ),
		style: blockStyles,
	} );

	return (
		<div { ...blockProps }>
			<BlockControls>
				<AlignmentToolbar
					value={ textAlign }
					onChange={ ( value ) => setAttributes( { textAlign: value } ) }
				/>
				<ToolbarGroup>
					<ToolbarButton
						icon={ grid }
						label={ __( 'Convert to Grid', 'author-profile-blocks' ) }
						onClick={ convertToGrid }
					/>
					<ToolbarButton
						icon={ update }
						label={ __( 'Refresh Authors', 'author-profile-blocks' ) }
						onClick={ () => {
							// Refreshing happens automatically through the hook system
						} }
					/>
				</ToolbarGroup>
			</BlockControls>

			<InspectorControls>
				<ContentPanel
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<LayoutPanel
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<StylePanel
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<AdvancedPanel
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>

			{ authorIds.length === 0 ? (
				<AuthorBlockPlaceholder
					icon={ list }
					title={ __( 'Author List', 'author-profile-blocks' ) }
					instructions={ __(
						'Select authors to display in a customizable list format.',
						'author-profile-blocks',
					) }
					selectedAuthorIds={ authorIds }
					onChange={ handleAuthorIdsChange }
					buttonLabel={ __( 'Select Authors', 'author-profile-blocks' ) }
				/>
			) : (
				<AuthorListPreview
					isLoading={ isLoading }
					authors={ authors }
					attributes={ attributes }
					error={ error }
				/>
			) }
		</div>
	);
}
