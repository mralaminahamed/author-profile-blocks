/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Placeholder, Spinner } from '@wordpress/components';
import { list as listIcon } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import AuthorItem from './AuthorItem';

/**
 * Author list preview component for the editor.
 *
 * @param {Object} props Component props.
 * @return {JSX.Element} Element to render.
 */
const AuthorsListPreview = ({
    isLoading,
    authors,
    attributes,
    error
}) => {
    if (isLoading) {
        return (
            <Placeholder>
                <Spinner />
                <p>{__('Loading authors...', 'author-profile-blocks')}</p>
            </Placeholder>
        );
    }

    if (error) {
        return (
            <Placeholder
                icon={listIcon}
                label={__('Author List', 'author-profile-blocks')}
                instructions={error}
            />
        );
    }

    if (!authors || !authors.length) {
        return (
            <div className="apb-author-list-empty">
                {__('No authors available to display.', 'author-profile-blocks')}
            </div>
        );
    }

    const {
        displayStyle = 'compact',
        listStyle = 'ul',
        enableDividers = false,
        dividerColor = '#e0e0e0',
        enableRounded = false,
        enableHoverEffect = false,
        backgroundColor,
        itemBackgroundColor,
        textAlign,
        blockPadding,
        itemPadding,
        itemSpacing,
        showImage = true,
        showPosition = true,
        showEmail = false,
        showDescription = false,
        showSocial = false,
    } = attributes;

    // Block level styles
    const blockStyles = {
        backgroundColor: backgroundColor || undefined,
        padding: blockPadding ? `${blockPadding}px` : undefined,
        textAlign: textAlign || undefined,
    };

    // List styles
    const listStyles = {
        gap: itemSpacing ? `${itemSpacing}px` : undefined,
    };

    // Item styles
    const itemStyles = {
        backgroundColor: itemBackgroundColor || undefined,
        padding: itemPadding ? `${itemPadding}px` : undefined,
        borderColor: enableDividers ? dividerColor : undefined,
    };

    // List classes
    const listClasses = [
        'apb-author-list',
        enableDividers ? 'has-dividers' : '',
    ].filter(Boolean).join(' ');

    // Render author item
    const renderAuthorItem = (author) => {
        const itemClasses = [
            'apb-author-list-item',
            enableRounded ? 'is-rounded' : '',
            enableHoverEffect ? 'has-hover-effect' : '',
        ].filter(Boolean).join(' ');

        return (
            <li key={author.id} className={itemClasses} style={itemStyles}>
                <div className="apb-author-list-item-content">
                    <AuthorItem 
                        author={author}
                        layout={displayStyle}
                        options={{
                            showImage,
                            showPosition,
                            showEmail,
                            showDescription,
                            showSocial
                        }}
                    />
                </div>
            </li>
        );
    };

    // Determine list tag based on listStyle
    const ListTag = listStyle === 'ol' ? 'ol' : 'ul';

    return (
        <div className="wp-block-author-profile-blocks-author-list" style={blockStyles}>
            <ListTag className={listClasses} style={listStyles}>
                {authors.map((author) => renderAuthorItem(author))}
            </ListTag>
        </div>
    );
};

export default AuthorsListPreview;
