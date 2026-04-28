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
	// eslint-disable-next-line no-unused-vars
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

	const blockProps = useBlockProps( {
		className: [
			textAlign ? `has-text-align-${ textAlign }` : '',
			layoutPreset ? layoutPreset : '',
			animationType && animationType !== 'none' ? `has-${ animationType }-animation` : '',
			hoverEffect && hoverEffect !== 'none' ? `has-${ hoverEffect }-hover` : '',
			customCssClass ? customCssClass : '',
			googleFont ? `has-${ googleFont.toLowerCase().replace( /\s+/g, '-' ) }-font` : '',
		].filter( Boolean ).join( ' ' ),
		style: {
			'--author-carousel-margin': margin || '',
			'--author-carousel-section-spacing': sectionSpacing ? `${ sectionSpacing }px` : '',
			'--author-carousel-container-width': containerWidth || '',
			'--author-carousel-custom-var-1': customVar1 || '',
			'--author-carousel-custom-var-2': customVar2 || '',
		},
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
