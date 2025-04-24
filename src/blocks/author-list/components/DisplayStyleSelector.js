/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button, ButtonGroup, Flex, FlexItem } from '@wordpress/components';
import { columns, tablet } from '@wordpress/icons';

/**
 * Display style selector component for the Author List block.
 *
 * @param {Object} props Component props.
 * @return {WPElement} Element to render.
 */
export default function DisplayStyleSelector({ value, onChange }) {
    const styles = [
        {
            name: 'compact',
            label: __('Compact', 'author-profile-blocks'),
            icon: tablet,
        },
        {
            name: 'detailed',
            label: __('Detailed', 'author-profile-blocks'),
            icon: columns,
        },
    ];

    return (
        <div className="apb-display-style-selector">
            <Flex gap={2} direction="column">
                <FlexItem>
                    <span className="components-base-control__label">
                        {__('Display Style', 'author-profile-blocks')}
                    </span>
                </FlexItem>
                <FlexItem>
                    <ButtonGroup>
                        {styles.map((style) => (
                            <Button
                                key={style.name}
                                icon={style.icon}
                                label={style.label}
                                isPressed={value === style.name}
                                onClick={() => onChange(style.name)}
                                showTooltip
                            />
                        ))}
                    </ButtonGroup>
                </FlexItem>
            </Flex>
        </div>
    );
}
