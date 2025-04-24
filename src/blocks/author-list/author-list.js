/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Author list component for the editor.
 * 
 * @param {Object} props Component props.
 * @return {WPElement} Element to render.
 */
export default function AuthorList({ authors, attributes }) {
    const {
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

    if (!authors || !authors.length) {
        return (
            <div className="apb-author-list-empty">
                {__('No authors available to display.', 'author-profile-blocks')}
            </div>
        );
    }

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
                    {displayStyle === 'detailed'
                        ? renderDetailedLayout(author)
                        : renderCompactLayout(author)
                    }
                </div>
            </li>
        );
    };

    // Render compact layout
    const renderCompactLayout = (author) => {
        return (
            <div className="apb-author-list-compact">
                {showImage && author.avatar_urls && (
                    <div className="apb-author-image">
                        <img
                            src={author.avatar_urls['96']}
                            alt={author.name}
                            loading="lazy"
                        />
                    </div>
                )}

                <div className="apb-author-list-info">
                    <h3 className="apb-author-name">{author.name}</h3>
                    {showPosition && author.roles && author.roles.length > 0 && (
                        <div className="apb-author-position">
                            {author.roles[0].toUpperCase()}
                        </div>
                    )}
                </div>

                {showSocial && (
                    <div className="apb-author-list-social">
                        {renderSocialIcons(author)}
                    </div>
                )}
            </div>
        );
    };

    // Render detailed layout
    const renderDetailedLayout = (author) => {
        return (
            <div className="apb-author-list-detailed">
                <div className="apb-author-list-left">
                    {showImage && author.avatar_urls && (
                        <div className="apb-author-image">
                            <img
                                src={author.avatar_urls['96']}
                                alt={author.name}
                                loading="lazy"
                            />
                        </div>
                    )}
                </div>

                <div className="apb-author-list-right">
                    <div className="apb-author-list-header">
                        <h3 className="apb-author-name">{author.name}</h3>
                        {showPosition && author.roles && author.roles.length > 0 && (
                            <div className="apb-author-position">
                                {author.roles[0].toUpperCase()}
                            </div>
                        )}
                    </div>

                    {showEmail && author.email && (
                        <div className="apb-author-email">
                            <a href={`mailto:${author.email}`}>{author.email}</a>
                        </div>
                    )}

                    {showDescription && author.description && (
                        <div className="apb-author-description">
                            {author.description}
                        </div>
                    )}

                    {showSocial && (
                        <div className="apb-author-list-footer">
                            {renderSocialIcons(author)}
                        </div>
                    )}
                </div>
            </div>
        );
    };

    // Render social icons
    const renderSocialIcons = (author) => {
        // Mock social icons for preview
        // In a real implementation, you'd extract these from author meta
        const socialProfiles = {
            facebook: author.id % 2 === 0 ? 'https://facebook.com' : '',
            twitter: author.id % 3 === 0 ? 'https://twitter.com' : '',
            linkedin: author.id % 5 === 0 ? 'https://linkedin.com' : '',
            instagram: author.id % 7 === 0 ? 'https://instagram.com' : '',
            website: author.url || '',
        };

        const socialIcons = {
            facebook: 'dashicons-facebook',
            twitter: 'dashicons-twitter',
            linkedin: 'dashicons-linkedin',
            instagram: 'dashicons-instagram',
            website: 'dashicons-admin-site',
        };

        return (
            <div className="apb-social-profiles">
                <ul className="apb-social-list">
                    {Object.entries(socialProfiles).map(([network, url]) => {
                        if (url) {
                            return (
                                <li
                                    key={network}
                                    className={`apb-social-item apb-social-${network}`}
                                >
                                    <a href="#" onClick={(e) => e.preventDefault()}>
                                        <span
                                            className={`dashicons ${socialIcons[network]}`}
                                            aria-hidden="true"
                                        ></span>
                                        <span className="screen-reader-text">
                                            {network.charAt(0).toUpperCase() + network.slice(1)}
                                        </span>
                                    </a>
                                </li>
                            );
                        }
                        return null;
                    })}
                </ul>
            </div>
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
}
