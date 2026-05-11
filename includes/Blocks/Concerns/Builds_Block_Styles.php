<?php
declare(strict_types=1);
/**
 * Builds_Block_Styles trait
 *
 * Assembles inline style arrays and the inline style string for author
 * blocks based on their attributes. Extracted from Author_Block_Base for
 * separation of concerns. The using class must declare a
 * `protected string $block_name` property which this trait reads via
 * `$this->block_name`.
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Blocks\Concerns;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Builds_Block_Styles {

	/**
	 * Get block styles based on attributes.
	 *
	 * @param array<string, mixed> $attributes Block attributes.
	 *
	 * @return array<string, string> {
	 *     CSS styles as associative array.
	 *
	 *     @type string $property CSS property name.
	 *     @type string $value    CSS property value.
	 * }
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

		// Margin
		if ( isset( $attributes['margin'] ) && ! empty( $attributes['margin'] ) ) {
			$styles['margin'] = $attributes['margin'];
		}

		// Container width — profile uses width directly; other blocks expose a CSS var
		// so the block's SCSS can apply max-width + auto-centering via attribute selector.
		if ( isset( $attributes['containerWidth'] ) && ! empty( $attributes['containerWidth'] ) ) {
			if ( 'author-profile' === $this->block_name ) {
				$styles['max-width'] = $attributes['containerWidth'];
				$styles['margin-inline'] = 'auto';
			} else {
				$styles[ '--' . $this->block_name . '-container-width' ] = $attributes['containerWidth'];
			}
		}

		// Border styles
		if ( isset( $attributes['borderWidth'] ) && $attributes['borderWidth'] > 0 ) {
			$styles['border-width'] = $attributes['borderWidth'] . 'px';
			$styles['border-style'] = 'solid';

			if ( ! empty( $attributes['borderColor'] ) ) {
				$styles['border-color'] = $attributes['borderColor'];
			}
		}

		if ( isset( $attributes['borderRadius'] ) && $attributes['borderRadius'] > 0 ) {
			$styles['border-radius'] = $attributes['borderRadius'] . 'px';
		}

		// Box shadow
		if ( ! empty( $attributes['boxShadow'] ) ) {
			$h_offset = isset( $attributes['boxShadowHorizontal'] ) ? $attributes['boxShadowHorizontal'] : 0;
			$v_offset = isset( $attributes['boxShadowVertical'] ) ? $attributes['boxShadowVertical'] : 4;
			$blur     = isset( $attributes['boxShadowBlur'] ) ? $attributes['boxShadowBlur'] : 8;
			$spread   = isset( $attributes['boxShadowSpread'] ) ? $attributes['boxShadowSpread'] : 0;
			$color    = ! empty( $attributes['boxShadowColor'] ) ? $attributes['boxShadowColor'] : 'rgba(0,0,0,0.2)';

			$styles['box-shadow'] = $h_offset . 'px ' . $v_offset . 'px ' . $blur . 'px ' . $spread . 'px ' . $color;
		}

		// Section spacing custom property
		if ( isset( $attributes['sectionSpacing'] ) ) {
			$styles['--author-section-spacing'] = $attributes['sectionSpacing'] . 'px';
		}

		// Animation duration
		if ( isset( $attributes['animationDuration'] ) ) {
			$styles['--author-animation-duration'] = $attributes['animationDuration'] . 'ms';
		}

		// Transform properties
		if ( isset( $attributes['transformScale'] ) && $attributes['transformScale'] !== 1 ) {
			$styles['--author-transform-scale'] = $attributes['transformScale'];
		}

		if ( isset( $attributes['transformRotate'] ) && $attributes['transformRotate'] !== 0 ) {
			$styles['--author-transform-rotate'] = $attributes['transformRotate'] . 'deg';
		}

		// Filter properties
		if ( isset( $attributes['filterBrightness'] ) && $attributes['filterBrightness'] !== 100 ) {
			$styles['--author-filter-brightness'] = $attributes['filterBrightness'] . '%';
		}

		if ( isset( $attributes['filterContrast'] ) && $attributes['filterContrast'] !== 100 ) {
			$styles['--author-filter-contrast'] = $attributes['filterContrast'] . '%';
		}

		if ( isset( $attributes['filterSaturate'] ) && $attributes['filterSaturate'] !== 100 ) {
			$styles['--author-filter-saturate'] = $attributes['filterSaturate'] . '%';
		}

		// Gradient background
		if ( ! empty( $attributes['gradientBackground'] ) ) {
			$start_color = $attributes['gradientStartColor'] ?? '#ffffff';
			$end_color   = $attributes['gradientEndColor'] ?? '#f8f9fa';
			$direction   = $attributes['gradientDirection'] ?? 'to bottom';

			$styles['background'] = 'linear-gradient(' . $direction . ', ' . $start_color . ', ' . $end_color . ')';
		}

		// Custom CSS variables
		if ( isset( $attributes['customVar1'] ) && ! empty( $attributes['customVar1'] ) ) {
			$styles['--author-custom-var-1'] = $attributes['customVar1'];
		}

		if ( isset( $attributes['customVar2'] ) && ! empty( $attributes['customVar2'] ) ) {
			$styles['--author-custom-var-2'] = $attributes['customVar2'];
		}

		// Avatar custom properties
		if ( isset( $attributes['avatarSize'] ) ) {
			$styles['--author-avatar-size'] = $attributes['avatarSize'] . 'px';
		}

		if ( isset( $attributes['avatarBorderWidth'] ) ) {
			$styles['--author-avatar-border-width'] = $attributes['avatarBorderWidth'] . 'px';
		}

		if ( ! empty( $attributes['avatarBorderColor'] ) ) {
			$styles['--author-avatar-border-color'] = $attributes['avatarBorderColor'];
		}

		if ( ! empty( $attributes['avatarShape'] ) && $attributes['avatarShape'] === 'custom' && isset( $attributes['avatarBorderRadius'] ) ) {
			$styles['--author-avatar-border-radius'] = $attributes['avatarBorderRadius'] . 'px';
		}

		if ( ! empty( $attributes['avatarAlignment'] ) ) {
			$styles['--author-avatar-align'] = $attributes['avatarAlignment'];
			$flex_justify = array(
				'left'   => 'flex-start',
				'center' => 'center',
				'right'  => 'flex-end',
			);
			$styles['--author-avatar-justify'] = $flex_justify[ $attributes['avatarAlignment'] ] ?? 'flex-start';
		}

		if ( isset( $attributes['avatarMargin'] ) ) {
			$styles['--author-avatar-margin'] = $attributes['avatarMargin'] . 'px';
		}

		// Name custom properties
		if ( isset( $attributes['nameSize'] ) ) {
			$styles['--author-name-size'] = $attributes['nameSize'] . 'px';
		}

		if ( ! empty( $attributes['nameWeight'] ) ) {
			$styles['--author-name-weight'] = $attributes['nameWeight'];
		}

		if ( ! empty( $attributes['nameColor'] ) ) {
			$styles['--author-name-color'] = $attributes['nameColor'];
		}

		if ( ! empty( $attributes['nameTransform'] ) ) {
			$styles['--author-name-transform'] = $attributes['nameTransform'];
		}

		if ( ! empty( $attributes['nameAlignment'] ) ) {
			$styles['--author-name-align'] = $attributes['nameAlignment'];
		}

		if ( isset( $attributes['nameMargin'] ) ) {
			$styles['--author-name-margin'] = $attributes['nameMargin'] . 'px';
		}

		// Description custom properties
		if ( isset( $attributes['descriptionSize'] ) ) {
			$styles['--author-description-size'] = $attributes['descriptionSize'] . 'px';
		}

		if ( isset( $attributes['descriptionLineHeight'] ) ) {
			$styles['--author-description-line-height'] = $attributes['descriptionLineHeight'];
		}

		if ( ! empty( $attributes['descriptionColor'] ) ) {
			$styles['--author-description-color'] = $attributes['descriptionColor'];
		}

		if ( ! empty( $attributes['descriptionStyle'] ) ) {
			$styles['--author-description-style'] = $attributes['descriptionStyle'];
		}

		if ( ! empty( $attributes['descriptionAlignment'] ) ) {
			$styles['--author-description-align'] = $attributes['descriptionAlignment'];
		}

		if ( isset( $attributes['descriptionMargin'] ) ) {
			$styles['--author-description-margin'] = $attributes['descriptionMargin'] . 'px';
		}

		// Meta custom properties
		if ( isset( $attributes['metaSize'] ) ) {
			$styles['--author-meta-size'] = $attributes['metaSize'] . 'px';
		}

		if ( ! empty( $attributes['metaColor'] ) ) {
			$styles['--author-meta-color'] = $attributes['metaColor'];
		}

		if ( ! empty( $attributes['metaStyle'] ) ) {
			$styles['--author-meta-style'] = $attributes['metaStyle'];
		}

		if ( isset( $attributes['metaBold'] ) && $attributes['metaBold'] ) {
			$styles['--author-meta-weight'] = 'bold';
		}

		if ( ! empty( $attributes['metaAlignment'] ) ) {
			$styles['--author-meta-align'] = $attributes['metaAlignment'];
		}

		if ( isset( $attributes['metaMargin'] ) ) {
			$styles['--author-meta-margin'] = $attributes['metaMargin'] . 'px';
		}

		// Email link custom properties
		if ( ! empty( $attributes['emailLinkColor'] ) ) {
			$styles['--author-email-link-color'] = $attributes['emailLinkColor'];
		}

		if ( ! empty( $attributes['emailHoverColor'] ) ) {
			$styles['--author-email-link-hover-color'] = $attributes['emailHoverColor'];
		}

		// Social icon custom properties
		if ( isset( $attributes['socialIconSize'] ) ) {
			$styles['--author-social-icon-size'] = $attributes['socialIconSize'] . 'px';
		}

		if ( ! empty( $attributes['socialIconColor'] ) ) {
			$styles['--author-social-icon-color'] = $attributes['socialIconColor'];
		}

		if ( ! empty( $attributes['socialIconHoverColor'] ) ) {
			$styles['--author-social-icon-hover-color'] = $attributes['socialIconHoverColor'];
		}

		if ( ! empty( $attributes['socialIconBackground'] ) ) {
			$styles['--author-social-icon-bg'] = $attributes['socialIconBackground'];
		}

		if ( ! empty( $attributes['socialIconBackgroundHover'] ) ) {
			$styles['--author-social-icon-bg-hover'] = $attributes['socialIconBackgroundHover'];
		}

		if ( isset( $attributes['socialIconSpacing'] ) ) {
			$styles['--author-social-icon-spacing'] = $attributes['socialIconSpacing'] . 'px';
		}

		if ( ! empty( $attributes['socialIconAlignment'] ) ) {
			$styles['--author-social-icon-align'] = $attributes['socialIconAlignment'];
		}

		// More content custom properties
		if ( ! empty( $attributes['moreContentBorderColor'] ) ) {
			$styles['--author-more-content-border-color'] = $attributes['moreContentBorderColor'];
		}

		if ( isset( $attributes['moreContentPadding'] ) ) {
			$styles['--author-more-content-padding'] = $attributes['moreContentPadding'] . 'px';
		}

		// Custom CSS variables
		if ( ! empty( $attributes['customVar1'] ) ) {
			$styles['--author-profile-custom-var-1'] = $attributes['customVar1'];
		}

		if ( ! empty( $attributes['customVar2'] ) ) {
			$styles['--author-profile-custom-var-2'] = $attributes['customVar2'];
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
	 * @param array<string, mixed> $attributes Block attributes.
	 *
	 * @return array<string, string> {
	 *     CSS styles as associative array.
	 *
	 *     @type string $property CSS property name.
	 *     @type string $value    CSS property value.
	 * }
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

		// Animation duration for items
		if ( isset( $attributes['animationDuration'] ) ) {
			$styles['animation-duration'] = $attributes['animationDuration'] . 'ms';
		}

		// Transform scale
		if ( isset( $attributes['transformScale'] ) && $attributes['transformScale'] !== 1 ) {
			$styles['transform'] = 'scale(' . $attributes['transformScale'] . ')';
		}

		// Transform rotate
		if ( isset( $attributes['transformRotate'] ) && $attributes['transformRotate'] !== 0 ) {
			$current_transform   = $styles['transform'] ?? '';
			$rotate_transform    = 'rotate(' . $attributes['transformRotate'] . 'deg)';
			$styles['transform'] = $current_transform ? $current_transform . ' ' . $rotate_transform : $rotate_transform;
		}

		// Filter properties
		$filters = array();
		if ( isset( $attributes['filterBrightness'] ) && $attributes['filterBrightness'] !== 100 ) {
			$filters[] = 'brightness(' . $attributes['filterBrightness'] . '%)';
		}
		if ( isset( $attributes['filterContrast'] ) && $attributes['filterContrast'] !== 100 ) {
			$filters[] = 'contrast(' . $attributes['filterContrast'] . '%)';
		}
		if ( isset( $attributes['filterSaturate'] ) && $attributes['filterSaturate'] !== 100 ) {
			$filters[] = 'saturate(' . $attributes['filterSaturate'] . '%)';
		}
		if ( ! empty( $filters ) ) {
			$styles['filter'] = implode( ' ', $filters );
		}

		return $styles;
	}

	/**
	 * Convert styles array to inline style string.
	 *
	 * @param array<string, string> $styles {
	 *     Array of CSS property:value pairs.
	 *
	 *     @type string $property CSS property name.
	 *     @type string $value    CSS property value.
	 * }
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
