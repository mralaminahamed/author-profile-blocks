/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, BlockControls, AlignmentToolbar } from '@wordpress/block-editor';
import {
    PanelBody,
    ToggleControl,
    Button
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import './editor.scss';
import useAuthors from './hooks/useAuthors';
import AuthorSelector from './components/AuthorSelector';
import AuthorPreview from './components/AuthorPreview';
import MoreContent from './components/MoreContent';

/**
 * The edit function for the Author Profile block.
 *
 * @param {Object} props Block properties.
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
    const { authorId, showMoreContent, moreContent, textAlign, showImage, showEmail, showDescription } = attributes;

    // Use our custom hook to manage authors
    const { authors, selectedAuthor, setSelectedAuthor, isLoading } = useAuthors(authorId);

    const blockProps = useBlockProps({
        className: textAlign ? `has-text-align-${textAlign}` : '',
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
                <PanelBody title={__('Author Profile Settings', 'wp-author-showcase')}>
                    <ToggleControl
                        label={__('Show Author Image', 'wp-author-showcase')}
                        checked={showImage}
                        onChange={() => setAttributes({ showImage: !showImage })}
                    />

                    <ToggleControl
                        label={__('Show Author Email', 'wp-author-showcase')}
                        checked={showEmail}
                        onChange={() => setAttributes({ showEmail: !showEmail })}
                    />

                    <ToggleControl
                        label={__('Show Author Description', 'wp-author-showcase')}
                        checked={showDescription}
                        onChange={() => setAttributes({ showDescription: !showDescription })}
                    />

                    <ToggleControl
                        label={__('Show More Content Section', 'wp-author-showcase')}
                        checked={showMoreContent}
                        onChange={() => setAttributes({ showMoreContent: !showMoreContent })}
                    />

                    {authorId > 0 && (
                        <Button
                            isDestructive
                            variant="secondary"
                            className="wpas-clear-button"
                            onClick={handleClearAuthor}
                        >
                            {__('Clear Selected Author', 'wp-author-showcase')}
                        </Button>
                    )}
                </PanelBody>
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
