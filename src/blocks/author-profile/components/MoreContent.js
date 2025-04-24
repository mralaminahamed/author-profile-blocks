/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { RichText } from '@wordpress/block-editor';
import { Card, CardHeader, CardBody, Icon } from '@wordpress/components';
import { formatBold, formatItalic, formatListBullets, formatListNumbered, link } from '@wordpress/icons';

/**
 * MoreContent component for additional content
 *
 * @param {Object}   props                  Component props
 * @param {string}   props.content          The current content
 * @param {Function} props.onContentChange  Callback for when content changes
 * @return {JSX.Element} Component to render
 */
const MoreContent = ({ content, onContentChange }) => {
    return (
        <div className="wpas-author-more-content">
            <Card className="wpas-more-content-card" elevation={1}>
                <CardHeader className="wpas-more-content-header">
                    <div className="wpas-more-content-title">
                        <h3>{__('Additional Information', 'author-profile-blocks')}</h3>
                        <p className="wpas-more-content-description">
                            {__('Add supplementary content about this author', 'author-profile-blocks')}
                        </p>
                    </div>
                    <div className="wpas-more-content-toolbar">
                        <div className="wpas-formatting-hint">
                            <Icon icon={formatBold} size={16} />
                            <Icon icon={formatItalic} size={16} />
                            <Icon icon={formatListBullets} size={16} />
                            <Icon icon={formatListNumbered} size={16} />
                            <Icon icon={link} size={16} />
                        </div>
                    </div>
                </CardHeader>
                <CardBody className="wpas-more-content-body">
                    <RichText
                        tagName="div"
                        value={content}
                        onChange={onContentChange}
                        placeholder={__('Add additional author information such as biography, achievements, or contact details...', 'author-profile-blocks')}
                        className="wpas-more-content-editor"
                        allowedFormats={['core/bold', 'core/italic', 'core/link', 'core/list']}
                    />
                </CardBody>
            </Card>
        </div>
    );
};

export default MoreContent;
