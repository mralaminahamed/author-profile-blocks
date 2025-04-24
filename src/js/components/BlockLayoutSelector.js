/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button, ButtonGroup } from '@wordpress/components';

/**
 * Unified layout selector component for all author blocks
 *
 * @param {Object} props Component props
 * @param {Array} props.layouts Array of layout options
 * @param {string} props.selectedLayout Currently selected layout value
 * @param {Function} props.onSelectLayout Callback when layout is selected
 * @param {string} props.className Additional CSS class
 * @return {JSX.Element} Element to render
 */
export default function BlockLayoutSelector({
    layouts = [],
    selectedLayout,
    onSelectLayout,
    className = '',
}) {
    if (!layouts || !layouts.length) {
        return null;
    }

    const selectorClass = `apb-block-layout-selector ${className}`.trim();

    return (
        <div className={selectorClass}>
            <span className="apb-layout-selector-label">
                {__('Select Layout:', 'author-profile-blocks')}
            </span>
            <ButtonGroup>
                {layouts.map((layout) => (
                    <Button
                        key={layout.value}
                        variant={selectedLayout === layout.value ? 'primary' : 'secondary'}
                        onClick={() => onSelectLayout(layout.value)}
                        className="apb-layout-selector-button"
                        aria-label={layout.label}
                        label={layout.label}
                        showTooltip
                    >
                        {layout.icon || layout.label}
                    </Button>
                ))}
            </ButtonGroup>
        </div>
    );
}
