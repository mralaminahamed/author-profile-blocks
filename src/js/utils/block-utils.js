/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Block helper utilities
 */

/**
 * Get common block styles from attributes
 *
 * @param {Object} attributes Block attributes
 * @return {Object} Block styles
 */
export const getBlockStyles = (attributes) => {
    const { 
        backgroundColor, 
        blockPadding,
        textAlign,
        border,
        borderColor,
        borderRadius
    } = attributes;

    return {
        backgroundColor: backgroundColor || undefined,
        padding: blockPadding ? `${blockPadding}px` : undefined,
        textAlign: textAlign || undefined,
        border: border ? `${border}px solid ${borderColor || '#ddd'}` : undefined,
        borderRadius: borderRadius ? `${borderRadius}px` : undefined
    };
};

/**
 * Get common item styles from attributes
 *
 * @param {Object} attributes Block attributes
 * @return {Object} Item styles
 */
export const getItemStyles = (attributes) => {
    const { 
        itemBackgroundColor, 
        itemPadding,
        itemBorder,
        itemBorderColor,
        itemBorderRadius,
        enableDividers,
        dividerColor
    } = attributes;

    return {
        backgroundColor: itemBackgroundColor || undefined,
        padding: itemPadding ? `${itemPadding}px` : undefined,
        border: itemBorder ? `${itemBorder}px solid ${itemBorderColor || '#ddd'}` : undefined,
        borderRadius: itemBorderRadius ? `${itemBorderRadius}px` : undefined,
        borderColor: enableDividers ? dividerColor : undefined,
    };
};

/**
 * Get common layout classes for items
 *
 * @param {Object} attributes Block attributes
 * @return {string} Class string
 */
export const getItemClasses = (attributes) => {
    const {
        enableRounded = false,
        enableHoverEffect = false,
        enableShadow = false,
        enableBorder = false
    } = attributes;

    return [
        enableRounded ? 'is-rounded' : '',
        enableHoverEffect ? 'has-hover-effect' : '',
        enableShadow ? 'has-shadow' : '',
        enableBorder ? 'has-border' : ''
    ].filter(Boolean).join(' ');
};

/**
 * Get common container classes
 *
 * @param {Object} attributes Block attributes
 * @param {string} blockName Block name
 * @return {string} Class string
 */
export const getContainerClasses = (attributes, blockName) => {
    const {
        align,
        textAlign,
        enableDividers = false
    } = attributes;

    return [
        `wp-block-author-profile-blocks-${blockName}`,
        align ? `align${align}` : '',
        textAlign ? `has-text-align-${textAlign}` : '',
        enableDividers ? 'has-dividers' : ''
    ].filter(Boolean).join(' ');
};
