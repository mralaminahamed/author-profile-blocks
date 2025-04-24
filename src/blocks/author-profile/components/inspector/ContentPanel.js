/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
    PanelBody,
    ToggleControl,
    Button,
    Card,
    CardBody,
    CheckboxControl,
} from '@wordpress/components';

/**
 * ContentPanel component for content-related settings in the InspectorControls
 * 
 * @param {Object} props Component props
 * @param {Object} props.attributes Block attributes
 * @param {Function} props.setAttributes Function to set block attributes
 * @param {Function} props.handleClearAuthor Function to clear author selection
 * @return {JSX.Element} Content panel component
 */
const ContentPanel = ({ attributes, setAttributes, handleClearAuthor }) => {
    const {
        authorId,
        showImage,
        showEmail,
        showDescription,
        showRegisteredDate,
        showMoreContent,
        showSocialLinks,
        socialLinksToShow,
    } = attributes;

    return (
        <>
            <PanelBody title={__('Author Selection', 'author-profile-blocks')}>
                {authorId > 0 && (
                    <Button
                        isDestructive
                        variant="secondary"
                        className="wpas-clear-button"
                        onClick={handleClearAuthor}
                    >
                        {__('Clear Selected Author', 'author-profile-blocks')}
                    </Button>
                )}
            </PanelBody>

            <PanelBody title={__('Content Elements', 'author-profile-blocks')}>
                <ToggleControl
                    label={__('Show Author Image', 'author-profile-blocks')}
                    checked={showImage}
                    onChange={() => setAttributes({ showImage: !showImage })}
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
                    label={__('Show More Section', 'author-profile-blocks')}
                    checked={showMoreContent}
                    onChange={() => setAttributes({ showMoreContent: !showMoreContent })}
                />
                
                <ToggleControl
                    label={__('Show Social Links', 'author-profile-blocks')}
                    checked={showSocialLinks}
                    onChange={() => setAttributes({ showSocialLinks: !showSocialLinks })}
                />
                
                {showSocialLinks && (
                    <Card>
                        <CardBody>
                            <CheckboxControl
                                label={__('Facebook', 'author-profile-blocks')}
                                checked={socialLinksToShow?.includes('facebook')}
                                onChange={(checked) => {
                                    const currentLinks = socialLinksToShow || [];
                                    setAttributes({
                                        socialLinksToShow: checked 
                                            ? [...currentLinks, 'facebook']
                                            : currentLinks.filter(link => link !== 'facebook')
                                    });
                                }}
                            />
                            <CheckboxControl
                                label={__('Twitter', 'author-profile-blocks')}
                                checked={socialLinksToShow?.includes('twitter')}
                                onChange={(checked) => {
                                    const currentLinks = socialLinksToShow || [];
                                    setAttributes({
                                        socialLinksToShow: checked 
                                            ? [...currentLinks, 'twitter']
                                            : currentLinks.filter(link => link !== 'twitter')
                                    });
                                }}
                            />
                            <CheckboxControl
                                label={__('LinkedIn', 'author-profile-blocks')}
                                checked={socialLinksToShow?.includes('linkedin')}
                                onChange={(checked) => {
                                    const currentLinks = socialLinksToShow || [];
                                    setAttributes({
                                        socialLinksToShow: checked 
                                            ? [...currentLinks, 'linkedin']
                                            : currentLinks.filter(link => link !== 'linkedin')
                                    });
                                }}
                            />
                            <CheckboxControl
                                label={__('Instagram', 'author-profile-blocks')}
                                checked={socialLinksToShow?.includes('instagram')}
                                onChange={(checked) => {
                                    const currentLinks = socialLinksToShow || [];
                                    setAttributes({
                                        socialLinksToShow: checked 
                                            ? [...currentLinks, 'instagram']
                                            : currentLinks.filter(link => link !== 'instagram')
                                    });
                                }}
                            />
                        </CardBody>
                    </Card>
                )}
            </PanelBody>
        </>
    );
};

export default ContentPanel;