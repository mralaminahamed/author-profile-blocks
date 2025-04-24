/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Placeholder, Button } from '@wordpress/components';

/**
 * A highly customizable placeholder component for block editors
 * 
 * This component serves as the primary empty state for all block types (grid, list, carousel)
 * and can be customized for each specific block's needs.
 *
 * @param {Object} props Component props
 * @param {string} props.icon The icon to display in the placeholder
 * @param {string} props.title The title of the placeholder
 * @param {string} props.instructions Instructions displayed in the placeholder
 * @param {JSX.Element} props.children Child components to render inside the placeholder
 * @param {JSX.Element} props.actions Action buttons/controls to be displayed at the bottom
 * @param {string} props.className Additional CSS class for placeholder
 * @param {boolean} props.isColumnLayout Whether to use column layout
 * @return {JSX.Element} Element to render
 */
export default function UnifiedBlockPlaceholder({
    icon,
    title,
    instructions,
    children,
    actions = null,
    className = '',
    isColumnLayout = true,
}) {
    const placeholderClass = `apb-unified-block-placeholder ${className}`.trim();
    
    return (
        <Placeholder
            icon={icon}
            label={title}
            instructions={instructions}
            className={placeholderClass}
            isColumnLayout={isColumnLayout}
        >
            <div className="apb-placeholder-content">
                {children}
            </div>
            
            {actions && (
                <div className="apb-placeholder-actions">
                    {actions}
                </div>
            )}
        </Placeholder>
    );
}
