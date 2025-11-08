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
import { PanelBody, Button } from '@wordpress/components';
import { grid } from '@wordpress/icons';
import { useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './editor.scss';
import { AuthorBlockPlaceholder } from '../../js/components';
import AuthorGridPreview from './components/AuthorGridPreview';
import GridLayoutSelector from './components/GridLayoutSelector';
import {
	ContentPanel,
	StylePanel,
	LayoutPanel,
	AdvancedPanel,
} from './components/inspector';
import type { AuthorGridAttributes } from '../../types/blocks';

/**
 * Edit component props
 */
interface EditProps {
	attributes: AuthorGridAttributes;
	setAttributes: (attributes: Partial<AuthorGridAttributes>) => void;
	clientId?: string;
}

/**
 * The edit function for the Author Grid block.
 *
 * @param {EditProps} props Block properties.
 * @return {JSX.Element} Element to render.
 */
export default function Edit({ attributes, setAttributes }: EditProps) {
	// eslint-disable-next-line no-unused-vars
	const {
		authorIds,
		columns,
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
		itemSpacing,
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
	useEffect(() => {
		if (googleFont && googleFont !== '') {
			loadGoogleFont(googleFont);
		}
	}, [ googleFont ]);

	const loadGoogleFont = (fontName) => {
		if (! fontName) {
			return;
		}

		// Remove existing Google Fonts link if present
		const existingLink = document.querySelector(
			'link[href*="fonts.googleapis.com"]',
		);
		if (existingLink) {
			existingLink.remove();
		}

		// Add new Google Font
		const link = document.createElement('link');
		link.href = `https://fonts.googleapis.com/css2?family=${ encodeURIComponent(fontName) }:wght@300;400;500;600;700&display=swap`;
		link.rel = 'stylesheet';
		document.head.appendChild(link);
	};

	const blockProps = useBlockProps({
		className: [
			textAlign ? `has-text-align-${ textAlign }` : '',
			layoutPreset ? layoutPreset : '',
			animationType && animationType !== 'none'
				? `has-${ animationType }-animation`
				: '',
			hoverEffect && hoverEffect !== 'none'
				? `has-${ hoverEffect }-hover`
				: '',
			customCssClass ? customCssClass : '',
			googleFont
				? `has-${ googleFont.toLowerCase().replace(/\s+/g, '-') }-font`
				: '',
		]
			.filter(Boolean)
			.join(' '),
		style: {
			'--author-grid-margin': margin || '',
			'--author-grid-section-spacing': sectionSpacing
				? `${ sectionSpacing }px`
				: '',
			'--author-grid-container-width': containerWidth || '',
			'--author-grid-custom-var-1': customVar1 || '',
			'--author-grid-custom-var-2': customVar2 || '',
		},
	});

	// Handle author selection
	const handleAuthorIdsChange = (selectedIds) => {
		setAttributes({ authorIds: selectedIds });
	};

	// Handle layout selection
	const handleSelectLayout = (newLayout) => {
		setAttributes({ layout: newLayout });
	};

	// Clear all selected authors
	const handleClearAuthors = () => {
		setAttributes({ authorIds: [] });
	};

	return (
		<>
			<BlockControls>
				<AlignmentToolbar
					value={ textAlign }
					onChange={ (newAlign) =>
						setAttributes({ textAlign: newAlign })
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
						title={ __('Author Selection', 'author-profile-blocks') }
					>
						<Button
							isDestructive
							variant="secondary"
							className="wpas-clear-button"
							onClick={ handleClearAuthors }
						>
							{ __('Clear Authors', 'author-profile-blocks') }
						</Button>
					</PanelBody>
				) }
			</InspectorControls>

			<div { ...blockProps }>
				{ ! authorIds.length ? (
					<AuthorBlockPlaceholder
						icon={ grid }
						title={ __('Author Grid', 'author-profile-blocks') }
						instructions={ __(
							'Select authors to display in a responsive grid layout.',
							'author-profile-blocks',
						) }
						selectedAuthorIds={ authorIds }
						onChange={ handleAuthorIdsChange }
						buttonLabel={ __(
							'Add Author to Grid',
							'author-profile-blocks',
						) }
					/>
				) : (
					<div className="apb-author-grid-preview">
						<GridLayoutSelector
							selectedLayout={ layout }
							onSelectLayout={ handleSelectLayout }
						/>
						,
						<AuthorGridPreview attributes={ attributes } />
					</div>
				) }
			</div>
		</>
	);
}
