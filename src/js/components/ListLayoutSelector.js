/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button, Flex, FlexItem } from '@wordpress/components';
import { useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './../../scss/common/_list-layout-selector.scss';

/**
 * Enhanced visual layout selector component for list blocks
 *
 * @param {Object} props Component props
 * @param {string} props.value Current selected value
 * @param {Function} props.onChange Callback when selection changes
 * @param {Object} props.style Optional styles
 * @return {JSX.Element} Component to render
 */
const ListLayoutSelector = ({ value, onChange, style = {} }) => {
    const [selectedValue, setSelectedValue] = useState(value || 'default');

    const handleSelection = (newValue) => {
        setSelectedValue(newValue);
        onChange(newValue);
    };

    const options = [
        {
            value: 'default',
            label: __('Default', 'author-profile-blocks'),
            icon: (
                <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                    <rect x="3" y="4" width="18" height="3" rx="1" />
                    <rect x="3" y="10.5" width="18" height="3" rx="1" />
                    <rect x="3" y="17" width="18" height="3" rx="1" />
                </svg>
            ),
        },
        {
            value: 'stacked',
            label: __('Stacked', 'author-profile-blocks'),
            icon: (
                <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                    <rect x="3" y="3" width="18" height="5" rx="1" />
                    <rect x="3" y="9.5" width="18" height="5" rx="1" />
                    <rect x="3" y="16" width="18" height="5" rx="1" />
                </svg>
            ),
        },
        {
            value: 'card',
            label: __('Card', 'author-profile-blocks'),
            icon: (
                <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                    <rect x="3" y="3" width="18" height="18" rx="2" />
                    <line x1="3" y1="7" x2="21" y2="7" stroke="currentColor" strokeWidth="1.5" />
                    <line x1="7" y1="12" x2="17" y2="12" stroke="currentColor" strokeWidth="1.5" />
                    <line x1="7" y1="16" x2="14" y2="16" stroke="currentColor" strokeWidth="1.5" />
                </svg>
            ),
        },
    ];

    return (
        <div className="apb-list-layout-selector" style={style}>
            <h4 className="apb-selector-title">{__('List Layout', 'author-profile-blocks')}</h4>

            <Flex gap={2} justify="flex-start" className="apb-layout-options">
                {options.map((option) => (
                    <FlexItem key={option.value} className="apb-layout-option-item">
                        <Button
                            className={`apb-layout-option ${selectedValue === option.value ? 'is-selected' : ''}`}
                            onClick={() => handleSelection(option.value)}
                            aria-pressed={selectedValue === option.value}
                        >
                            <div className="apb-layout-option-icon">
                                {option.icon}
                            </div>
                            <span className="apb-layout-option-label">{option.label}</span>
                        </Button>
                    </FlexItem>
                ))}
            </Flex>
        </div>
    );
};

export default ListLayoutSelector;