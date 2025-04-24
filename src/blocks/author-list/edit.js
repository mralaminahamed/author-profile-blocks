/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
    useBlockProps,
    InspectorControls,
    BlockControls,
    AlignmentToolbar,
} from '@wordpress/block-editor';
import {
    PanelBody,
    ToggleControl,
    RangeControl,
    SelectControl,
    ColorPicker,
    ToolbarGroup,
    ToolbarButton,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { createBlock } from '@wordpress/blocks';
import { list, grid, update } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import './editor.scss';
import { AuthorPicker, AuthorBlockPlaceholder } from '../../js/components';
import AuthorListPreview from './components/AuthorListPreview';
import DisplayStyleSelector from './components/DisplayStyleSelector';
import ListLayoutSelector from './components/ListLayoutSelector';
import useAuthors from './hooks/useAuthors';

/**
 * Edit function for the Author List block.
 *
 * @param {Object} props Block properties.
 * @return {JSX.Element} Element to render.
 */
export default function Edit({ attributes, setAttributes, clientId, insertBlocks }) {
    const {
        authorIds,
        authorRole,
        maxAuthors,
        displayStyle,
        listStyle,
        enableDividers,
        dividerColor,
        enableRounded,
        enableHoverEffect,
        backgroundColor,
        itemBackgroundColor,
        textAlign,
        blockPadding,
        itemPadding,
        itemSpacing,
        showImage,
        showPosition,
        showEmail,
        showDescription,
        showSocial,
    } = attributes;

    // Use our custom hook to fetch and manage authors
    const { authors, isLoading, error } = useAuthors(authorIds, authorRole, maxAuthors);

    // Get roles for dropdown
    const authorRoles = useSelect((select) => {
        const { getRoles } = select('core');
        return getRoles ? getRoles() : [];
    }, []);

    // Format roles for dropdown
    const roleOptions = [
        { label: __('All Roles', 'author-profile-blocks'), value: '' },
    ];

    if (authorRoles && authorRoles.length) {
        authorRoles.forEach((role) => {
            roleOptions.push({ label: role.name, value: role.id });
        });
    }

    // Handle author IDs change
    const handleAuthorIdsChange = (newAuthorIds) => {
        setAttributes({ authorIds: newAuthorIds });
    };

    // Convert to Grid block
    const convertToGrid = () => {
        const gridBlock = createBlock('author-profile-blocks/author-grid', {
            authorIds,
            authorRole,
            maxAuthors,
            columns: 3,
            layout: displayStyle === 'detailed' ? 'card' : 'compact',
            backgroundColor,
            textAlign,
            showImage,
            showPosition,
            showEmail,
            showDescription,
            showSocial,
        });

        insertBlocks(gridBlock, undefined, clientId, true);
    };

    // Block props
    const blockProps = useBlockProps();

    return (
        <div {...blockProps}>
            <BlockControls>
                <AlignmentToolbar
                    value={textAlign}
                    onChange={(value) => setAttributes({ textAlign: value })}
                />
                <ToolbarGroup>
                    <ToolbarButton
                        icon={grid}
                        label={__('Convert to Grid', 'author-profile-blocks')}
                        onClick={convertToGrid}
                    />
                    <ToolbarButton
                        icon={update}
                        label={__('Refresh Authors', 'author-profile-blocks')}
                        onClick={() => {
                            // Refreshing happens automatically through the hook system
                        }}
                    />
                </ToolbarGroup>
            </BlockControls>

            <InspectorControls>
                <PanelBody
                    title={__('Author Selection', 'author-profile-blocks')}
                    initialOpen={true}
                >
                    <AuthorPicker
                        selectedAuthorIds={authorIds ?? []}
                        onChange={handleAuthorIdsChange}
                    />

                    <SelectControl
                        label={__('Filter by Role', 'author-profile-blocks')}
                        value={authorRole}
                        options={roleOptions}
                        onChange={(value) => setAttributes({ authorRole: value })}
                    />

                    <RangeControl
                        label={__('Maximum Authors to Display', 'author-profile-blocks')}
                        value={maxAuthors}
                        onChange={(value) => setAttributes({ maxAuthors: value })}
                        min={0}
                        max={100}
                        step={1}
                        help={__(
                            'Set to 0 to show all selected authors',
                            'author-profile-blocks'
                        )}
                    />
                </PanelBody>

                <PanelBody
                    title={__('List Settings', 'author-profile-blocks')}
                    initialOpen={true}
                >
                    <DisplayStyleSelector
                        value={displayStyle}
                        onChange={(value) => setAttributes({ displayStyle: value })}
						style={{ marginBottom: '1rem' }}
                    />

                    <ListLayoutSelector
                        value={listStyle}
                        onChange={(value) => setAttributes({ listStyle: value })}
						style={{ marginBottom: '1rem' }}
                    />

                    <ToggleControl
                        label={__('Show Dividers Between Items', 'author-profile-blocks')}
                        checked={enableDividers}
                        onChange={() => setAttributes({ enableDividers: !enableDividers })}
                    />

                    {enableDividers && (
                        <div className="apb-color-picker-label-wrapper">
                            <span>{__('Divider Color', 'author-profile-blocks')}</span>
                            <ColorPicker
                                color={dividerColor}
                                onChange={(value) => setAttributes({ dividerColor: value })}
                                enableAlpha
                            />
                        </div>
                    )}

                    <ToggleControl
                        label={__('Enable Rounded Corners', 'author-profile-blocks')}
                        checked={enableRounded}
                        onChange={() => setAttributes({ enableRounded: !enableRounded })}
                    />

                    <ToggleControl
                        label={__('Enable Hover Effect', 'author-profile-blocks')}
                        checked={enableHoverEffect}
                        onChange={() => setAttributes({ enableHoverEffect: !enableHoverEffect })}
                    />

                    <RangeControl
                        label={__('Space Between Items', 'author-profile-blocks')}
                        value={itemSpacing}
                        onChange={(value) => setAttributes({ itemSpacing: value })}
                        min={0}
                        max={50}
                        step={1}
                    />

                    <RangeControl
                        label={__('Block Padding', 'author-profile-blocks')}
                        value={blockPadding}
                        onChange={(value) => setAttributes({ blockPadding: value })}
                        min={0}
                        max={50}
                        step={1}
                    />

                    <RangeControl
                        label={__('Item Padding', 'author-profile-blocks')}
                        value={itemPadding}
                        onChange={(value) => setAttributes({ itemPadding: value })}
                        min={0}
                        max={50}
                        step={1}
                    />
                </PanelBody>

                <PanelBody title={__('Colors', 'author-profile-blocks')}>
                    <div className="apb-color-picker-label-wrapper">
                        <span>{__('Block Background', 'author-profile-blocks')}</span>
                        <ColorPicker
                            color={backgroundColor}
                            onChange={(value) => setAttributes({ backgroundColor: value })}
                            enableAlpha
                        />
                    </div>

                    <div className="apb-color-picker-label-wrapper">
                        <span>{__('Item Background', 'author-profile-blocks')}</span>
                        <ColorPicker
                            color={itemBackgroundColor}
                            onChange={(value) => setAttributes({ itemBackgroundColor: value })}
                            enableAlpha
                        />
                    </div>
                </PanelBody>

                <PanelBody title={__('Display Elements', 'author-profile-blocks')}>
                    <ToggleControl
                        label={__('Show Author Image', 'author-profile-blocks')}
                        checked={showImage}
                        onChange={() => setAttributes({ showImage: !showImage })}
                    />

                    <ToggleControl
                        label={__('Show Position/Role', 'author-profile-blocks')}
                        checked={showPosition}
                        onChange={() => setAttributes({ showPosition: !showPosition })}
                    />

                    <ToggleControl
                        label={__('Show Email', 'author-profile-blocks')}
                        checked={showEmail}
                        onChange={() => setAttributes({ showEmail: !showEmail })}
                    />

                    <ToggleControl
                        label={__('Show Description', 'author-profile-blocks')}
                        checked={showDescription}
                        onChange={() => setAttributes({ showDescription: !showDescription })}
                    />

                    <ToggleControl
                        label={__('Show Social Links', 'author-profile-blocks')}
                        checked={showSocial}
                        onChange={() => setAttributes({ showSocial: !showSocial })}
                    />
                </PanelBody>
            </InspectorControls>

            {authorIds.length === 0 ? (
                <AuthorBlockPlaceholder
                    icon={list}
                    title={__('Author List', 'author-profile-blocks')}
                    instructions={__(
                        'Select authors to display in a customizable list format.',
                        'author-profile-blocks'
                    )}
                    selectedAuthorIds={authorIds}
                    onChange={handleAuthorIdsChange}
                    buttonLabel={__('Select Authors', 'author-profile-blocks')}
                />
            ) : (
                <AuthorListPreview
                    isLoading={isLoading}
                    authors={authors}
                    attributes={attributes}
                    error={error}
                />
            )}
        </div>
    );
}
