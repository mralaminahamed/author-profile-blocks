/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	AlignmentToolbar,
	BlockControls,
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';
import {
	PanelBody,
	Button,
} from '@wordpress/components';
import { useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './editor.scss';
import { AuthorBlockPlaceholder } from '../../supports/js/components';
import AuthorCarouselPreview from './components/AuthorCarouselPreview';
import CarouselLayoutSelector from './components/CarouselLayoutSelector';
import { ContentPanel, LayoutPanel, StylePanel, AdvancedPanel } from './components/inspector';
import { shuffle } from '@wordpress/icons';

/**
 * The edit function for the Author Carousel block.
 *
 * @param {Object}   props               Block properties.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Function to update attributes.
 * @return {JSX.Element} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	const {
		authorIds,
		slidesToShow,
		autoplay,
		autoplaySpeed,
		showDots,
		showArrows,
		infinite,
		showImage,
		showEmail,
		showDescription,
		showPosition,
		showSocial,
		showRegisteredDate,
		layout,
		textAlign,
		backgroundColor,
		padding,
		slideSpacing,
		enableShadow,
		enableBorder,
		borderColor,
		borderWidth,
		enableRounded,
		maxAuthors,
		authorRole,
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
		borderRadius,
		containerWidth,
		customVar1,
		customVar2,
	} = attributes;

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

	const getShadowStyle = () => {
		if ( ! boxShadow ) return undefined;
		return `${ boxShadowHorizontal || 0 }px ${ boxShadowVertical || 4 }px ${ boxShadowBlur || 8 }px ${ boxShadowSpread || 0 }px ${ boxShadowColor || 'rgba(0,0,0,0.2)' }`;
	};

	const blockStyles = {
		backgroundColor: backgroundColor || undefined,
		background: gradientBackground
			? `linear-gradient(${ gradientDirection || 'to bottom' }, ${ gradientStartColor || '#ffffff' }, ${ gradientEndColor || '#000000' })`
			: undefined,
		padding: padding ? `${ padding }px` : undefined,
		margin: margin || undefined,
		width: containerWidth || undefined,
		boxShadow: boxShadow ? getShadowStyle() : undefined,
		borderWidth: enableBorder && borderWidth ? `${ borderWidth }px` : undefined,
		borderStyle: enableBorder && borderWidth ? 'solid' : undefined,
		borderColor: enableBorder ? borderColor : undefined,
		borderRadius: enableRounded && borderRadius ? `${ borderRadius }px` : undefined,
		transform: ( transformScale && transformScale !== 1 ) || transformRotate
			? `scale(${ transformScale || 1 }) rotate(${ transformRotate || 0 }deg)`
			: undefined,
		filter: ( filterBrightness && filterBrightness !== 100 ) ||
			( filterContrast && filterContrast !== 100 ) ||
			( filterSaturate && filterSaturate !== 100 )
			? `brightness(${ filterBrightness || 100 }%) contrast(${ filterContrast || 100 }%) saturate(${ filterSaturate || 100 }%)`
			: undefined,
		'--author-carousel-slides-to-show': slidesToShow || undefined,
		'--author-carousel-slide-spacing': slideSpacing ? `${ slideSpacing }px` : undefined,
		'--author-carousel-section-spacing': sectionSpacing ? `${ sectionSpacing }px` : undefined,
		'--author-carousel-container-width': containerWidth || undefined,
		'--author-carousel-custom-var-1': customVar1 || undefined,
		'--author-carousel-custom-var-2': customVar2 || undefined,
	};

	const blockProps = useBlockProps( {
		className: [
			textAlign ? `has-text-align-${ textAlign }` : '',
			layoutPreset ? layoutPreset : '',
			animationType && animationType !== 'none' ? `has-${ animationType }-animation` : '',
			hoverEffect && hoverEffect !== 'none' ? `has-${ hoverEffect }-hover` : '',
			customCssClass ? customCssClass : '',
			googleFont ? `has-${ googleFont.toLowerCase().replace( /\s+/g, '-' ) }-font` : '',
		].filter( Boolean ).join( ' ' ),
		style: blockStyles,
	} );

	// Handle author selection
	const handleAuthorIdsChange = ( selectedIds ) => {
		setAttributes( { authorIds: selectedIds } );
	};

	// Handle layout selection
	const handleSelectLayout = ( newLayout ) => {
		setAttributes( { layout: newLayout } );
	};

	return (
		<>
			<BlockControls>
				<AlignmentToolbar
					value={ textAlign }
					onChange={ ( newAlign ) =>
						setAttributes( { textAlign: newAlign } )
					}
				/>
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

				{ authorIds.length > 0 && (
					<PanelBody
						title={ __( 'Author Selection', 'author-profile-blocks' ) }
					>
						<Button
							isDestructive
							variant="secondary"
							className="wpas-clear-button"
							onClick={ () => setAttributes( { authorIds: [] } ) }
						>
							{ __( 'Clear Authors', 'author-profile-blocks' ) }
						</Button>
					</PanelBody>
				) }
			</InspectorControls>

			<div { ...blockProps }>
				{ ! authorIds.length ? (
					<AuthorBlockPlaceholder
						icon={ shuffle }
						title={ __( 'Author Carousel', 'author-profile-blocks' ) }
						instructions={ __(
							'Select authors to display in an interactive carousel.',
							'author-profile-blocks',
						) }
						selectedAuthorIds={ authorIds }
						onChange={ handleAuthorIdsChange }
						buttonLabel={ __(
							'Add Author to Carousel',
							'author-profile-blocks',
						) }
						layoutSelector={
							<CarouselLayoutSelector
								selectedLayout={ layout }
								onSelectLayout={ handleSelectLayout }
							/>
						}
						className="apbl-author-carousel-placeholder"
					/>
				) : (
					<AuthorCarouselPreview attributes={ attributes } />
				) }
			</div>
		</>
	);
}
