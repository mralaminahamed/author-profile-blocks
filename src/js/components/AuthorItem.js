/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Common Author item component that provides various layouts
 * 
 * @param {Object} props Component props
 * @param {Object} props.author Author data object
 * @param {string} props.layout Layout to use (compact or detailed)
 * @param {Object} props.options Display options
 * @return {JSX.Element} Element to render
 */
const AuthorItem = ({ 
    author, 
    layout = 'compact',
    options = {}
}) => {
    const {
        showImage = true,
        showName = true,
        showPosition = true,
        showEmail = false,
        showDescription = false,
        showSocial = false,
        imageSizeClass = '',
        imageSize = '96', // 96px avatar size
        className = '',
        itemStyles = {},
    } = options;

    // Generate class names for the item
    const itemClasses = [
        'apb-author-item',
        `apb-layout-${layout}`,
        className,
    ].filter(Boolean).join(' ');

    // Get social profiles
    const getSocialProfiles = () => {
        // Get social profiles from user meta if available
        const socialProfiles = {
            facebook: author.meta?.facebook || '',
            twitter: author.meta?.twitter || '',
            linkedin: author.meta?.linkedin || '',
            instagram: author.meta?.instagram || '',
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

    // Get position/role text
    const getPositionText = () => {
        // First check for custom position meta if available
        if (author.meta?.position) {
            return author.meta.position;
        }
        
        // Otherwise use first role
        if (author.roles && author.roles.length > 0) {
            return author.roles[0].toUpperCase();
        }
        
        return '';
    };

    // Compact layout
    if (layout === 'compact') {
        return (
            <div className={itemClasses} style={itemStyles}>
                <div className="apb-author-compact">
                    {showImage && author.avatar_urls && (
                        <div className={`apb-author-image ${imageSizeClass}`}>
                            <img
                                src={author.avatar_urls[imageSize] || author.avatar_urls['96']}
                                alt={author.name}
                                loading="lazy"
                            />
                        </div>
                    )}

                    <div className="apb-author-info">
                        {showName && (
                            <h3 className="apb-author-name">{author.name}</h3>
                        )}
                        
                        {showPosition && getPositionText() && (
                            <div className="apb-author-position">
                                {getPositionText()}
                            </div>
                        )}
                        
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
                    </div>

                    {showSocial && (
                        <div className="apb-author-social">
                            {getSocialProfiles()}
                        </div>
                    )}
                </div>
            </div>
        );
    }

    // Detailed layout
    return (
        <div className={itemClasses} style={itemStyles}>
            <div className="apb-author-detailed">
                <div className="apb-author-left">
                    {showImage && author.avatar_urls && (
                        <div className={`apb-author-image ${imageSizeClass}`}>
                            <img
                                src={author.avatar_urls[imageSize] || author.avatar_urls['96']}
                                alt={author.name}
                                loading="lazy"
                            />
                        </div>
                    )}
                </div>

                <div className="apb-author-right">
                    <div className="apb-author-header">
                        {showName && (
                            <h3 className="apb-author-name">{author.name}</h3>
                        )}
                        
                        {showPosition && getPositionText() && (
                            <div className="apb-author-position">
                                {getPositionText()}
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
                        <div className="apb-author-footer">
                            {getSocialProfiles()}
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
};

export default AuthorItem;
