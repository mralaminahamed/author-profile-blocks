/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {TextareaControl} from '@wordpress/components';

/**
 * MoreContent component for additional content
 *
 * @param {Object}   props                  Component props
 * @param {string}   props.content          The current content
 * @param {Function} props.onContentChange  Callback for when content changes
 * @return {WPElement} Component to render
 */
const MoreContent = ({ content, onContentChange }) => {
    return (
        <div className="wpas-author-more-content">
            <TextareaControl
                value={content}
                onChange={onContentChange}
                placeholder={__('Add additional content here...', 'wp-author-showcase')}
                multiline="p"
            />
        </div>
    );
};

export default MoreContent;
