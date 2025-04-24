/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { ButtonGroup, Button } from '@wordpress/components';

/**
 * WordPress global
 */
const { AuthorProfileBlocks = { adminUrl: '/wp-admin/' } } = window;

/**
 * Grid layout selector component
 *
 * @param {Object} props             Component props
 * @param {string} props.selectedLayout Current selected layout
 * @param {Function} props.onSelectLayout Callback for layout selection
 * @return {WPElement} Component to render
 */
const GridLayoutSelector = ({ selectedLayout, onSelectLayout }) => {
    // Layout options
    const layouts = [
        {
            name: 'card',
            label: __('Card', 'author-profile-blocks'),
            icon: 'dashicons-cover-image',
        },
        {
            name: 'compact',
            label: __('Compact', 'author-profile-blocks'),
            icon: 'dashicons-id',
        },
        {
            name: 'centered',
            label: __('Centered', 'author-profile-blocks'),
            icon: 'dashicons-align-center',
        },
    ];

    return (
        <div className="apb-grid-layout-options">
            <ButtonGroup>
                {layouts.map((layout) => (
                    <Button
                        key={layout.name}
                        className={`apb-layout-option ${selectedLayout === layout.name ? 'is-selected' : ''}`}
                        isPressed={selectedLayout === layout.name}
                        onClick={() => onSelectLayout(layout.name)}
                    >
                        <span className={`dashicons ${layout.icon}`}></span>
                        <span>{layout.label}</span>
                    </Button>
                ))}
            </ButtonGroup>
        </div>
    );
};

export default GridLayoutSelector;
