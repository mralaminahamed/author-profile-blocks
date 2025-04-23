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
    RichText
} from '@wordpress/block-editor';
import {
    PanelBody,
    ToggleControl,
    Button,
    RangeControl,
    Card,
    CardHeader,
    CardBody,
    Icon
} from '@wordpress/components';
import { formatBold, formatItalic, formatListBullets, formatListNumbered, link } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import './editor.scss';
import useAuthors from './hooks/useAuthors';
import AuthorSelector from './components/AuthorSelector';
import AuthorPreview from './components/AuthorPreview';
import MoreContent from './components/MoreContent';

/**
 * WordPress global
 */
const { AuthorProfileShowcase = { adminUrl: '/wp-admin/' } } = window;

/**
 * The edit function for the Author Profile block.
 *
 * @param {Object} props Block properties.
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
    const {
        authorId,
        showMoreContent,
        moreContent,
        textAlign,
        showImage,
        showEmail,
        showDescription,
        backgroundColor,
        padding
    } = attributes;

    // Use our custom hook to manage authors
    const { authors, selectedAuthor, setSelectedAuthor, isLoading } = useAuthors(authorId);

    const blockProps = useBlockProps({
        className: textAlign ? `has-text-align-${textAlign}` : '',
        style: {
            backgroundColor: backgroundColor || undefined,
            padding: padding ? `${padding}px` : undefined
        }
    });

    // Handle author selection
    const handleSelectAuthor = (author) => {
        setAttributes({ authorId: author.id });
        setSelectedAuthor(author);
    };

    // Handle clearing the selected author
    const handleClearAuthor = () => {
        setAttributes({ authorId: 0 });
        setSelectedAuthor(null);
    };

    // Handle more content changes
    const handleMoreContentChange = (value) => {
        setAttributes({ moreContent: value });
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
                <PanelBody title={__('Display Settings', 'author-profile-showcase')}>
                    <ToggleControl
                        label={__('Show Author Image', 'author-profile-showcase')}
                        checked={showImage}
                        onChange={() => setAttributes({ showImage: !showImage })}
                    />

                    <ToggleControl
                        label={__('Show Author Email', 'author-profile-showcase')}
                        checked={showEmail}
                        onChange={() => setAttributes({ showEmail: !showEmail })}
                    />

                    <ToggleControl
                        label={__('Show Author Description', 'author-profile-showcase')}
                        checked={showDescription}
                        onChange={() => setAttributes({ showDescription: !showDescription })}
                    />

                    <ToggleControl
                        label={__('Show More Section', 'author-profile-showcase')}
                        checked={showMoreContent}
                        onChange={() => setAttributes({ showMoreContent: !showMoreContent })}
                    />
                </PanelBody>

                <PanelBody title={__('Style Settings', 'author-profile-showcase')}>
                    <RangeControl
                        label={__('Padding', 'author-profile-showcase')}
                        value={padding}
                        onChange={(value) => setAttributes({ padding: value })}
                        min={0}
                        max={50}
                        initialPosition={20}
                    />
                </PanelBody>

                <PanelColorSettings
                    title={__('Color Settings', 'author-profile-showcase')}
                    initialOpen={false}
                    colorSettings={[
                        {
                            value: backgroundColor,
                            onChange: (value) => setAttributes({ backgroundColor: value }),
                            label: __('Background Color', 'author-profile-showcase'),
                        }
                    ]}
                />

                {authorId > 0 && (
                    <PanelBody title={__('Author Selection', 'author-profile-showcase')}>
                        <Button
                            isDestructive
                            variant="secondary"
                            className="wpas-clear-button"
                            onClick={handleClearAuthor}
                        >
                            {__('Clear Selected Author', 'author-profile-showcase')}
                        </Button>
                    </PanelBody>
                )}
            </InspectorControls>

            <div {...blockProps}>
                {!authorId ? (
                    <AuthorSelector
                        authors={authors}
                        onSelectAuthor={handleSelectAuthor}
                        isLoading={isLoading}
                    />
                ) : (
                    <>
                        <AuthorPreview attributes={attributes} />

                        {showMoreContent && (
                            <MoreContent
                                content={moreContent}
                                onContentChange={handleMoreContentChange}
                            />
                        )}
                    </>
                )}
            </div>
        </>
    );
}
