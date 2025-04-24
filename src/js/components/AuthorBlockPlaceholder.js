/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
    Card,
    CardHeader,
    CardBody,
    CardFooter,
    Flex,
    FlexItem,
    Icon,
    Tooltip,
    CardDivider,
    Button
} from '@wordpress/components';
import { info } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import { AuthorPicker } from '../index';
import './../../scss/common/_placeholder.scss';

/**
 * Enhanced placeholder component for author blocks (list, grid, carousel)
 *
 * @param {Object} props Component props
 * @param {string} props.icon The icon to display in the placeholder
 * @param {string} props.title The title of the placeholder
 * @param {string} props.instructions Instructions displayed in the placeholder
 * @param {Array} props.selectedAuthorIds Currently selected author IDs
 * @param {Function} props.onChange Callback when authors selection changes
 * @param {string} props.buttonLabel Custom label for the add button
 * @param {JSX.Element} props.layoutSelector Optional layout selector component
 * @param {JSX.Element} props.additionalControls Any additional controls to include in the placeholder
 * @param {string} props.className Additional CSS class for placeholder
 * @return {JSX.Element} Element to render
 */
export default function AuthorBlockPlaceholder({
                                                   icon,
                                                   title,
                                                   instructions,
                                                   selectedAuthorIds = [],
                                                   onChange,
                                                   buttonLabel,
                                                   layoutSelector = null,
                                                   additionalControls = null,
                                                   className = '',
                                               }) {
    const placeholderClass = `apb-author-block-placeholder ${className}`.trim();

    return (
        <Card className={placeholderClass} elevation={3}>
            <CardHeader className="apb-placeholder-header">
                <Flex justify="space-between" align="center">
                    <FlexItem>
                        <Flex align="center" gap={2}>
                            <div className="apb-placeholder-icon">
                                <Icon icon={icon} size={24} />
                            </div>
                            <h3 className="apb-placeholder-title">{title}</h3>
                        </Flex>
                    </FlexItem>
                    <FlexItem>
                        <Tooltip text={__('Configure this author block', 'author-profile-blocks')}>
                            <Icon icon={info} size={16} />
                        </Tooltip>
                    </FlexItem>
                </Flex>
            </CardHeader>

            <CardDivider />

            <CardBody className="apb-placeholder-body">
                <div className="apb-placeholder-instructions-wrapper">
                    <p className="apb-placeholder-instructions">{instructions}</p>
                </div>

                <div className="apb-placeholder-content">
                    <AuthorPicker
                        selectedAuthorIds={selectedAuthorIds}
                        onChange={onChange}
                        buttonLabel={buttonLabel}
                    />
                </div>

                {layoutSelector && (
                    <div className="apb-layout-selector-wrapper">
                        <h4 className="apb-layout-selector-title">{__('Layout Options', 'author-profile-blocks')}</h4>
                        {layoutSelector}
                    </div>
                )}
            </CardBody>

            {(additionalControls || layoutSelector) && (
                <>
                    <CardDivider />
                    <CardFooter className="apb-placeholder-footer">
                        {additionalControls && (
                            <div className="apb-additional-controls">
                                {additionalControls}
                            </div>
                        )}
                        {!additionalControls && layoutSelector && (
                            <Flex justify="flex-end">
                                <Button
                                    variant="primary"
                                    className="apb-continue-button"
                                >
                                    {__('Continue', 'author-profile-blocks')}
                                </Button>
                            </Flex>
                        )}
                    </CardFooter>
                </>
            )}
        </Card>
    );
}