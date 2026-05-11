<?php
declare(strict_types=1);
/**
 * Builds_Block_Classes trait
 *
 * Assembles the wrapper className string for author blocks based on their
 * attributes. Extracted from Author_Block_Base for separation of concerns.
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Blocks\Concerns;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait BuildsBlockClasses {

	/**
	 * Get block classes based on attributes.
	 *
	 * @param array<string, mixed> $attributes Block attributes.
	 * @param string               $block_type The block type identifier. Default empty string.
	 *
	 * @return string Space-separated CSS classes.
	 */
	protected function get_block_classes( array $attributes, string $block_type = '' ): string {
		$classes = array();

		// Text alignment.
		if ( ! empty( $attributes['textAlign'] ) ) {
			$classes[] = 'has-text-align-' . $attributes['textAlign'];
		}

		// Add layout class.
		if ( ! empty( $attributes['layout'] ) ) {
			$classes[] = 'is-layout-' . $attributes['layout'];
		}

		// Add block style if specified
		if ( ! empty( $attributes['blockStyle'] ) ) {
			$classes[] = $attributes['blockStyle'];
		}

		// Add content order class
		if ( ! empty( $attributes['contentOrder'] ) ) {
			$classes[] = 'content-order-' . $attributes['contentOrder'];
		}

		// Add layout preset class if specified
		if ( ! empty( $attributes['layoutPreset'] ) ) {
			$classes[] = $attributes['layoutPreset'];
		}

		// Add animation classes
		if ( ! empty( $attributes['animationType'] ) && $attributes['animationType'] !== 'none' ) {
			$classes[] = 'has-' . $attributes['animationType'] . '-animation';
		}

		// Add hover effect class
		if ( ! empty( $attributes['hoverEffect'] ) && $attributes['hoverEffect'] !== 'none' ) {
			$classes[] = 'has-' . $attributes['hoverEffect'] . '-hover';
		}

		// Add Google Font class
		if ( ! empty( $attributes['googleFont'] ) ) {
			$classes[] = 'has-' . sanitize_title( $attributes['googleFont'] ) . '-font';
		}

		// Add custom CSS class if specified
		if ( ! empty( $attributes['customCssClass'] ) ) {
			$classes[] = esc_attr( $attributes['customCssClass'] );
		}

		// Add display style for list block — uses apbl-display-* prefix (set in template)
		// Do NOT add is-style-* from displayStyle as it conflicts with layoutPreset classes.

		// Add classes based on display options.
		if ( ! empty( $attributes['showImage'] ) ) {
			$classes[] = 'has-author-image';
		}

		if ( ! empty( $attributes['showEmail'] ) ) {
			$classes[] = 'has-author-email';
		}

		if ( ! empty( $attributes['showDescription'] ) ) {
			$classes[] = 'has-author-description';
		}

		if ( ! empty( $attributes['showPosition'] ) ) {
			$classes[] = 'has-author-position';
		}

		if ( ! empty( $attributes['showRegisteredDate'] ) ) {
			$classes[] = 'has-registered-date';
		}

		if ( ! empty( $attributes['showSocialLinks'] ) || ! empty( $attributes['showSocial'] ) ) {
			$classes[] = 'has-social-profiles';
		}

		if ( ! empty( $attributes['showMoreContent'] ) ) {
			$classes[] = 'has-more-content';
		}

		// Add avatar shape class if specified
		if ( ! empty( $attributes['avatarShape'] ) ) {
			$classes[] = 'avatar-shape-' . $attributes['avatarShape'];
		}

		// Add box shadow class if enabled
		if ( ! empty( $attributes['boxShadow'] ) ) {
			$classes[] = 'has-box-shadow';
		}

		// Add shadow class if enabled.
		if ( ! empty( $attributes['enableShadow'] ) ) {
			$classes[] = 'has-shadow';
		}

		// Add border class if enabled.
		if ( ! empty( $attributes['enableBorder'] ) ) {
			$classes[] = 'has-border';
		}

		// Add rounded class if enabled.
		if ( ! empty( $attributes['enableRounded'] ) ) {
			$classes[] = 'is-rounded';
		}

		// Add hover effect class if enabled.
		if ( ! empty( $attributes['enableHoverEffect'] ) ) {
			$classes[] = 'has-hover-effect';
		}

		// Add block-specific class if provided.
		if ( ! empty( $block_type ) ) {
			$classes[] = 'is-block-' . $block_type;
		}

		return implode( ' ', $classes );
	}
}
