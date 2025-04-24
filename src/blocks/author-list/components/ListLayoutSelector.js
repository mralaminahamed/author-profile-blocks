/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button, ButtonGroup, Flex, FlexItem } from '@wordpress/components';
import { list, formatListBullets, formatListNumbered } from '@wordpress/icons';

/**
 * Layout selector component for the Author List block.
 *
 * @param {Object} props Component props.
 * @return {WPElement} Element to render.
 */
export default function ListLayoutSelector({ value, onChange }) {
    const layouts = [
        {
            name: 'ul',
            label: __('Unordered List', 'author-profile-blocks'),
            icon: formatListBullets,
        },
        {
            name: 'ol',
            label: __('Ordered List', 'author-profile-blocks'),
            icon: formatListNumbered,
        },
    ];

    return (
        <div className="apb-list-layout-selector">
            <Flex gap={2} direction="column">
                <FlexItem>
                    <span className="components-base-control__label">
                        {__('List Style', 'author-profile-blocks')}
                    </span>
                </FlexItem>
                <FlexItem>
                    <ButtonGroup>
                        {layouts.map((layout) => (
                            <Button
                                key={layout.name}
                                icon={layout.icon}
                                label={layout.label}
                                isPressed={value === layout.name}
                                onClick={() => onChange(layout.name)}
                                showTooltip
                            />
                        ))}
                    </ButtonGroup>
                </FlexItem>
            </Flex>
        </div>
    );
}
