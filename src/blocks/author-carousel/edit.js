/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
    useBlockProps,
    InspectorControls,
    BlockControls,
    AlignmentToolbar,
    PanelColorSettings,
} from '@wordpress/block-editor';
import {
    PanelBody,
    ToggleControl,
    Button,
    RangeControl,
    SelectControl,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import './editor.scss';
import { AuthorPicker } from '../../components';
import AuthorCarouselPreview from './components/AuthorCarouselPreview';
import CarouselLayoutSelector from './components/CarouselLayoutSelector';

/**
 * The edit function for the Author Carousel block.
 *
 * @param {Object} props Block properties.
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
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
        authorRole
    } = attributes;

    const blockProps = useBlockProps({
        className: textAlign ? `has-text-align-${textAlign}` : '',
    });

    // Handle author selection
    const handleAuthorIdsChange = (selectedIds) => {
        setAttributes({ authorIds: selectedIds });
    };

    // Handle layout selection
    const handleSelectLayout = (newLayout) => {
        setAttributes({ layout: newLayout });
    };

    return (
        <>
            <BlockControls>
                <AlignmentToolbar
                    value={textAlign}
                    onChange={(newAlign) => setAttributes({ textAlign: newAlign })}
                />
            </BlockControls>

            <InspectorControls>
                <PanelBody title={__('Author Selection', 'author-profile-blocks')} initialOpen={true}>
                    <AuthorPicker
                        selectedAuthorIds={authorIds}
                        onChange={handleAuthorIdsChange}
                    />

                    <SelectControl
                        label={__('Filter by Role', 'author-profile-blocks')}
                        value={authorRole}
                        options={[
                            { label: __('All Roles', 'author-profile-blocks'), value: '' },
                            { label: __('Administrator', 'author-profile-blocks'), value: 'administrator' },
                            { label: __('Editor', 'author-profile-blocks'), value: 'editor' },
                            { label: __('Author', 'author-profile-blocks'), value: 'author' },
                            { label: __('Contributor', 'author-profile-blocks'), value: 'contributor' },
                        ]}
                        onChange={(value) => setAttributes({ authorRole: value })}
                    />

                    <RangeControl
                        label={__('Maximum Authors', 'author-profile-blocks')}
                        value={maxAuthors}
                        onChange={(value) => setAttributes({ maxAuthors: value })}
                        min={1}
                        max={50}
                        initialPosition={10}
                    />
                </PanelBody>

                <PanelBody title={__('Carousel Settings', 'author-profile-blocks')}>
                    <RangeControl
                        label={__('Slides to Show', 'author-profile-blocks')}
                        value={slidesToShow}
                        onChange={(value) => setAttributes({ slidesToShow: value })}
                        min={1}
                        max={5}
                        initialPosition={3}
                    />

                    <RangeControl
                        label={__('Slide Spacing (px)', 'author-profile-blocks')}
                        value={slideSpacing}
                        onChange={(value) => setAttributes({ slideSpacing: value })}
                        min={0}
                        max={50}
                        initialPosition={20}
                    />

                    <ToggleControl
                        label={__('Autoplay', 'author-profile-blocks')}
                        checked={autoplay}
                        onChange={() => setAttributes({ autoplay: !autoplay })}
                    />

                    {autoplay && (
                        <RangeControl
                            label={__('Autoplay Speed (ms)', 'author-profile-blocks')}
                            value={autoplaySpeed}
                            onChange={(value) => setAttributes({ autoplaySpeed: value })}
                            min={1000}
                            max={10000}
                            step={500}
                            initialPosition={3000}
                        />
                    )}

                    <ToggleControl
                        label={__('Show Dots', 'author-profile-blocks')}
                        checked={showDots}
                        onChange={() => setAttributes({ showDots: !showDots })}
                    />

                    <ToggleControl
                        label={__('Show Arrows', 'author-profile-blocks')}
                        checked={showArrows}
                        onChange={() => setAttributes({ showArrows: !showArrows })}
                    />

                    <ToggleControl
                        label={__('Infinite Loop', 'author-profile-blocks')}
                        checked={infinite}
                        onChange={() => setAttributes({ infinite: !infinite })}
                    />
                </PanelBody>

                <PanelBody title={__('Display Settings', 'author-profile-blocks')}>
                    <ToggleControl
                        label={__('Show Author Image', 'author-profile-blocks')}
                        checked={showImage}
                        onChange={() => setAttributes({ showImage: !showImage })}
                    />

                    <ToggleControl
                        label={__('Show Author Position', 'author-profile-blocks')}
                        checked={showPosition}
                        onChange={() => setAttributes({ showPosition: !showPosition })}
                    />

                    <ToggleControl
                        label={__('Show Author Email', 'author-profile-blocks')}
                        checked={showEmail}
                        onChange={() => setAttributes({ showEmail: !showEmail })}
                    />

                    <ToggleControl
                        label={__('Show Author Description', 'author-profile-blocks')}
                        checked={showDescription}
                        onChange={() => setAttributes({ showDescription: !showDescription })}
                    />

                    <ToggleControl
                        label={__('Show Member Since Date', 'author-profile-blocks')}
                        checked={showRegisteredDate}
                        onChange={() => setAttributes({ showRegisteredDate: !showRegisteredDate })}
                    />

                    <ToggleControl
                        label={__('Show Social Links', 'author-profile-blocks')}
                        checked={showSocial}
                        onChange={() => setAttributes({ showSocial: !showSocial })}
                    />
                </PanelBody>

                <PanelBody title={__('Style Settings', 'author-profile-blocks')}>
                    <RangeControl
                        label={__('Item Padding (px)', 'author-profile-blocks')}
                        value={padding}
                        onChange={(value) => setAttributes({ padding: value })}
                        min={0}
                        max={50}
                        initialPosition={20}
                    />

                    <ToggleControl
                        label={__('Enable Shadow', 'author-profile-blocks')}
                        checked={enableShadow}
                        onChange={() => setAttributes({ enableShadow: !enableShadow })}
                    />

                    <ToggleControl
                        label={__('Enable Rounded Corners', 'author-profile-blocks')}
                        checked={enableRounded}
                        onChange={() => setAttributes({ enableRounded: !enableRounded })}
                    />

                    <ToggleControl
                        label={__('Enable Border', 'author-profile-blocks')}
                        checked={enableBorder}
                        onChange={() => setAttributes({ enableBorder: !enableBorder })}
                    />

                    {enableBorder && (
                        <RangeControl
                            label={__('Border Width (px)', 'author-profile-blocks')}
                            value={borderWidth}
                            onChange={(value) => setAttributes({ borderWidth: value })}
                            min={1}
                            max={10}
                            initialPosition={1}
                        />
                    )}
                </PanelBody>

                <PanelColorSettings
                    title={__('Color Settings', 'author-profile-blocks')}
                    initialOpen={false}
                    colorSettings={[
                        {
                            value: backgroundColor,
                            onChange: (value) => setAttributes({ backgroundColor: value }),
                            label: __('Background Color', 'author-profile-blocks'),
                        },
                        {
                            value: borderColor,
                            onChange: (value) => setAttributes({ borderColor: value }),
                            label: __('Border Color', 'author-profile-blocks'),
                        }
                    ]}
                />
            </InspectorControls>

            <div {...blockProps}>
                {!authorIds.length ? (
                    <div className="apb-author-carousel-placeholder">
                        <h3>{__('Author Carousel', 'author-profile-blocks')}</h3>
                        <p>{__('Select authors to display in an interactive carousel.', 'author-profile-blocks')}</p>
                        <AuthorPicker
                            selectedAuthorIds={authorIds}
                            onChange={handleAuthorIdsChange}
                            buttonLabel={__('Add Author to Carousel', 'author-profile-blocks')}
                        />
                    </div>
                ) : (
                    <div className="apb-author-carousel-preview">
                        <CarouselLayoutSelector
                            selectedLayout={layout}
                            onSelectLayout={handleSelectLayout}
                        />
                        <AuthorCarouselPreview attributes={attributes} />
                    </div>
                )}
            </div>
        </>
    );
}
