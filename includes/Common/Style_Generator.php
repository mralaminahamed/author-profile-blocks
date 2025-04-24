<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Style Generator Trait
 *
 * @package    AuthorProfileBlocks
 * @subpackage Common
 */

namespace AuthorProfileBlocks\Common;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait for generating styles and classes consistently across blocks.
 */
trait Style_Generator {
	/**
	 * Get block classes based on attributes.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $block_type The block type identifier.
	 *
	 * @return string CSS classes.
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

		// Add display style for list/grid blocks.
		if ( ! empty( $attributes['displayStyle'] ) ) {
			$classes[] = 'is-style-' . $attributes['displayStyle'];
		}

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

		if ( ! empty( $attributes['showSocial'] ) ) {
			$classes[] = 'has-social-profiles';
		}

		if ( ! empty( $attributes['showMoreContent'] ) ) {
			$classes[] = 'has-more-content';
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

	/**
	 * Get block styles based on attributes.
	 *
	 * @param array $attributes Block attributes.
	 *
	 * @return array CSS styles.
	 */
	protected function get_block_styles( array $attributes ): array {
		$styles = array();

		// Background color.
		if ( ! empty( $attributes['backgroundColor'] ) ) {
			$styles['background-color'] = $attributes['backgroundColor'];
		}

		// Padding.
		if ( isset( $attributes['padding'] ) ) {
			$styles['padding'] = $attributes['padding'] . 'px';
		} elseif ( isset( $attributes['blockPadding'] ) ) {
			$styles['padding'] = $attributes['blockPadding'] . 'px';
		}

		// Border color if enabled.
		if ( ! empty( $attributes['enableBorder'] ) && ! empty( $attributes['borderColor'] ) ) {
			$styles['border-color'] = $attributes['borderColor'];
		}

		// Border width if specified.
		if ( ! empty( $attributes['enableBorder'] ) && isset( $attributes['borderWidth'] ) ) {
			$styles['border-width'] = $attributes['borderWidth'] . 'px';
		}

		return $styles;
	}

	/**
	 * Get styles for individual items like list items or grid items.
	 *
	 * @param array $attributes Block attributes.
	 *
	 * @return array CSS styles.
	 */
	protected function get_item_styles( array $attributes ): array {
		$styles = array();

		// Item background color.
		if ( ! empty( $attributes['itemBackgroundColor'] ) ) {
			$styles['background-color'] = $attributes['itemBackgroundColor'];
		}

		// Padding.
		if ( isset( $attributes['itemPadding'] ) ) {
			$styles['padding'] = $attributes['itemPadding'] . 'px';
		}

		// Border color if dividers enabled.
		if ( ! empty( $attributes['enableDividers'] ) && ! empty( $attributes['dividerColor'] ) ) {
			$styles['border-color'] = $attributes['dividerColor'];
		}

		return $styles;
	}

	/**
	 * Convert styles array to inline style string.
	 *
	 * @param array $styles Array of CSS property:value pairs.
	 *
	 * @return string CSS inline style string.
	 */
	protected function get_styles_string( array $styles ): string {
		if ( empty( $styles ) ) {
			return '';
		}

		$style_strings = array();
		foreach ( $styles as $property => $value ) {
			$style_strings[] = $property . ': ' . $value;
		}

		return implode( '; ', $style_strings );
	}
}
