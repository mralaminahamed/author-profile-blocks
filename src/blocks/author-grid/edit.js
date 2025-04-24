/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
    useBlockProps,
    InspectorControls,
    BlockControls,
    AlignmentToolbar,
    PanelColorSettings,
} from '@wordpress/block-editor';
import {
    PanelBody,
    ToggleControl,
    Button,
    RangeControl,
    SelectControl,
} from '@wordpress/components';
import { grid } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import './editor.scss';
import { AuthorPicker, AuthorBlockPlaceholder } from '../../js/components';
import AuthorGridPreview from './components/AuthorGridPreview';
import GridLayoutSelector from './components/GridLayoutSelector';

/**
 * The edit function for the Author Grid block.
 *
 * @param {Object} props Block properties.
 * @return {JSX.Element} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
    const {
        authorIds,
        columns,
        showImage,
        showEmail,
        showDescription,
        showPosition,
        showSocial,
        showRegisteredDate,
        layout,
        textAlign,
        backgroundColor,
        padding,
        itemSpacing,
        enableShadow,
        enableBorder,
        borderColor,
        borderWidth,
        enableRounded,
        maxAuthors,
        authorRole
    } = attributes;

    const blockProps = useBlockProps({
        className: textAlign ? `has-text-align-${textAlign}` : '',
    });

    // Handle author selection
    const handleAuthorIdsChange = (selectedIds) => {
        setAttributes({ authorIds: selectedIds });
    };

    // Handle layout selection
    const handleSelectLayout = (newLayout) => {
        setAttributes({ layout: newLayout });
    };

    // Clear all selected authors
    const handleClearAuthors = () => {
        setAttributes({ authorIds: [] });
    };

    return (
        <>
            <BlockControls>
                <AlignmentToolbar
                    value={textAlign}
                    onChange={(newAlign) => setAttributes({ textAlign: newAlign })}
                />
            </BlockControls>

            <InspectorControls>
                <PanelBody title={__('Author Selection', 'author-profile-blocks')} initialOpen={true}>
                    <AuthorPicker
                        selectedAuthorIds={authorIds}
                        onChange={handleAuthorIdsChange}
                    />

                    <SelectControl
                        label={__('Filter by Role', 'author-profile-blocks')}
                        value={authorRole}
                        options={[
                            { label: __('All Roles', 'author-profile-blocks'), value: '' },
                            { label: __('Administrator', 'author-profile-blocks'), value: 'administrator' },
                            { label: __('Editor', 'author-profile-blocks'), value: 'editor' },
                            { label: __('Author', 'author-profile-blocks'), value: 'author' },
                            { label: __('Contributor', 'author-profile-blocks'), value: 'contributor' },
                        ]}
                        onChange={(value) => setAttributes({ authorRole: value })}
                    />

                    <RangeControl
                        label={__('Maximum Authors', 'author-profile-blocks')}
                        value={maxAuthors}
                        onChange={(value) => setAttributes({ maxAuthors: value })}
                        min={1}
                        max={50}
                        initialPosition={6}
                    />
                </PanelBody>

                <PanelBody title={__('Grid Settings', 'author-profile-blocks')}>
                    <RangeControl
                        label={__('Columns', 'author-profile-blocks')}
                        value={columns}
                        onChange={(value) => setAttributes({ columns: value })}
                        min={1}
                        max={4}
                        initialPosition={3}
                    />
                    <RangeControl
                        label={__('Item Spacing (px)', 'author-profile-blocks')}
                        value={itemSpacing}
                        onChange={(value) => setAttributes({ itemSpacing: value })}
                        min={0}
                        max={50}
                        initialPosition={20}
                    />
                </PanelBody>

                <PanelBody title={__('Display Settings', 'author-profile-blocks')}>
                    <ToggleControl
                        label={__('Show Author Image', 'author-profile-blocks')}
                        checked={showImage}
                        onChange={() => setAttributes({ showImage: !showImage })}
                    />

                    <ToggleControl
                        label={__('Show Author Position', 'author-profile-blocks')}
                        checked={showPosition}
                        onChange={() => setAttributes({ showPosition: !showPosition })}
                    />

                    <ToggleControl
                        label={__('Show Author Email', 'author-profile-blocks')}
                        checked={showEmail}
                        onChange={() => setAttributes({ showEmail: !showEmail })}
                    />

                    <ToggleControl
                        label={__('Show Author Description', 'author-profile-blocks')}
                        checked={showDescription}
                        onChange={() => setAttributes({ showDescription: !showDescription })}
                    />

                    <ToggleControl
                        label={__('Show Member Since Date', 'author-profile-blocks')}
                        checked={showRegisteredDate}
                        onChange={() => setAttributes({ showRegisteredDate: !showRegisteredDate })}
                    />

                    <ToggleControl
                        label={__('Show Social Links', 'author-profile-blocks')}
                        checked={showSocial}
                        onChange={() => setAttributes({ showSocial: !showSocial })}
                    />
                </PanelBody>

                <PanelBody title={__('Style Settings', 'author-profile-blocks')}>
                    <RangeControl
                        label={__('Item Padding (px)', 'author-profile-blocks')}
                        value={padding}
                        onChange={(value) => setAttributes({ padding: value })}
                        min={0}
                        max={50}
                        initialPosition={20}
                    />

                    <ToggleControl
                        label={__('Enable Shadow', 'author-profile-blocks')}
                        checked={enableShadow}
                        onChange={() => setAttributes({ enableShadow: !enableShadow })}
                    />

                    <ToggleControl
                        label={__('Enable Rounded Corners', 'author-profile-blocks')}
                        checked={enableRounded}
                        onChange={() => setAttributes({ enableRounded: !enableRounded })}
                    />

                    <ToggleControl
                        label={__('Enable Border', 'author-profile-blocks')}
                        checked={enableBorder}
                        onChange={() => setAttributes({ enableBorder: !enableBorder })}
                    />

                    {enableBorder && (
                        <RangeControl
                            label={__('Border Width (px)', 'author-profile-blocks')}
                            value={borderWidth}
                            onChange={(value) => setAttributes({ borderWidth: value })}
                            min={1}
                            max={10}
                            initialPosition={1}
                        />
                    )}
                </PanelBody>

                <PanelColorSettings
                    title={__('Color Settings', 'author-profile-blocks')}
                    initialOpen={false}
                    colorSettings={[
                        {
                            value: backgroundColor,
                            onChange: (value) => setAttributes({ backgroundColor: value }),
                            label: __('Background Color', 'author-profile-blocks'),
                        },
                        {
                            value: borderColor,
                            onChange: (value) => setAttributes({ borderColor: value }),
                            label: __('Border Color', 'author-profile-blocks'),
                        }
                    ]}
                />

				{authorIds > 0 && (
					<PanelBody title={__('Author Selection', 'author-profile-blocks')}>
						<Button
							isDestructive
							variant="secondary"
							className="wpas-clear-button"
							onClick={handleClearAuthors}
						>
							{__('Clear Authors', 'author-profile-blocks')}
						</Button>
					</PanelBody>
				)}
            </InspectorControls>

            <div {...blockProps}>
                {!authorIds.length ? (
                    <AuthorBlockPlaceholder
                        icon={grid}
                        title={__('Author Grid', 'author-profile-blocks')}
                        instructions={__('Select authors to display in a responsive grid layout.', 'author-profile-blocks')}
                        selectedAuthorIds={authorIds}
                        onChange={handleAuthorIdsChange}
                        buttonLabel={__('Add Author to Grid', 'author-profile-blocks')}
                    />
                ) : (
                    <div className="apb-author-grid-preview">
                        <GridLayoutSelector
                            selectedLayout={layout}
                            onSelectLayout={handleSelectLayout}
                        />
                        <AuthorGridPreview attributes={attributes} />
                    </div>
                )}
            </div>
        </>
    );
}
