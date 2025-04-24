/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Spinner } from '@wordpress/components';

/**
 * Unified component for displaying various block states (loading, error, empty)
 *
 * @param {Object} props Component props
 * @param {boolean} props.isLoading Whether content is loading
 * @param {string} props.error Error message if applicable
 * @param {boolean} props.isEmpty Whether the content is empty
 * @param {string} props.emptyMessage Message to display when content is empty
 * @param {string} props.loadingMessage Message to display during loading
 * @param {string} props.className Additional CSS class
 * @param {JSX.Element} props.icon Optional icon to display
 * @return {JSX.Element} Element to render
 */
export default function BlockStateDisplay({
    isLoading = false,
    error = '',
    isEmpty = false,
    emptyMessage = __('No content to display', 'author-profile-blocks'),
    loadingMessage = __('Loading...', 'author-profile-blocks'),
    className = '',
    icon = null,
}) {
    const stateClass = `apb-block-state-display ${className}`.trim();

    if (isLoading) {
        return (
            <div className={`${stateClass} is-loading`}>
                <Spinner />
                <p>{loadingMessage}</p>
            </div>
        );
    }

    if (error) {
        return (
            <div className={`${stateClass} has-error`}>
                <p className="apb-error-message">{error}</p>
            </div>
        );
    }

    if (isEmpty) {
        return (
            <div className={`${stateClass} is-empty`}>
                {icon && <div className="apb-state-icon">{icon}</div>}
                <p>{emptyMessage}</p>
            </div>
        );
    }

    return null;
}
