/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button, Flex, FlexItem, Icon } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { list, grid, columns } from '@wordpress/icons';

/**
 * Enhanced visual display style selector component
 *
 * @param {Object} props Component props
 * @param {string} props.value Current selected value
 * @param {Function} props.onChange Callback when selection changes
 * @param {Object} props.style Optional styles
 * @return {JSX.Element} Component to render
 */
const DisplayStyleSelector = ({ value, onChange, style = {} }) => {
    const [selectedValue, setSelectedValue] = useState(value || 'compact');

    const handleSelection = (newValue) => {
        setSelectedValue(newValue);
        onChange(newValue);
    };

    const options = [
        {
            value: 'compact',
            label: __('Compact', 'author-profile-blocks'),
            icon: list,
            description: __('Simple list with minimal details', 'author-profile-blocks'),
        },
        {
            value: 'standard',
            label: __('Standard', 'author-profile-blocks'),
            icon: columns,
            description: __('Balanced information display', 'author-profile-blocks'),
        },
        {
            value: 'detailed',
            label: __('Detailed', 'author-profile-blocks'),
            icon: grid,
            description: __('Full author information with bio', 'author-profile-blocks'),
        },
    ];

    return (
        <div className="apb-display-style-selector" style={style}>
            <h4 className="apb-selector-title">{__('Display Style', 'author-profile-blocks')}</h4>

            <Flex gap={2} justify="flex-start" className="apb-display-options">
                {options.map((option) => (
                    <FlexItem key={option.value} className="apb-display-option-item">
                        <Button
                            className={`apb-display-option ${selectedValue === option.value ? 'is-selected' : ''}`}
                            onClick={() => handleSelection(option.value)}
                            aria-pressed={selectedValue === option.value}
                        >
                            <div className="apb-display-option-icon">
                                <Icon icon={option.icon} size={24} />
                            </div>
                            <div className="apb-display-option-content">
                                <span className="apb-display-option-label">{option.label}</span>
                                <span className="apb-display-option-description">{option.description}</span>
                            </div>
                        </Button>
                    </FlexItem>
                ))}
            </Flex>
        </div>
    );
};

export default DisplayStyleSelector;