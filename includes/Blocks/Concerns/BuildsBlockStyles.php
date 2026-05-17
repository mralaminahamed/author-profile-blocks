<?php
declare(strict_types=1);
/**
 * Builds_Block_Styles trait
 *
 * Assembles inline style arrays and the inline style string for author
 * blocks based on their attributes. Extracted from AuthorBlockBase for
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

trait BuildsBlockStyles {

	/**
	 * Get block styles based on attributes.
	 *
	 * @param array<string, mixed> $attributes Block attributes.
	 *
	 * @return array<string, string> CSS styles as associative array.
	 */
	protected function get_block_styles( array $attributes ): array {
		$styles = array();

		// Background color.
		if ( ! empty( $attributes['backgroundColor'] ) ) {
			$color = $this->sanitize_css_color( $attributes['backgroundColor'] );
			if ( $color ) {
				$styles['background-color'] = $color;
			}
		}

		// Padding.
		if ( isset( $attributes['padding'] ) ) {
			$styles['padding'] = $attributes['padding'] . 'px';
		} elseif ( isset( $attributes['blockPadding'] ) ) {
			$styles['padding'] = $attributes['blockPadding'] . 'px';
		}

		// Margin.
		if ( isset( $attributes['margin'] ) && '' !== $attributes['margin'] ) {
			$length = $this->sanitize_css_length( (string) $attributes['margin'] );
			if ( $length ) {
				$styles['margin'] = $length;
			}
		}

		// Container width.
		if ( isset( $attributes['containerWidth'] ) && '' !== $attributes['containerWidth'] ) {
			$length = $this->sanitize_css_length( (string) $attributes['containerWidth'] );
			if ( $length ) {
				if ( 'author-profile' === $this->block_name ) {
					$styles['max-width']     = $length;
					$styles['margin-inline'] = 'auto';
				} else {
					$styles[ '--' . $this->block_name . '-container-width' ] = $length;
				}
			}
		}

		// Border styles.
		if ( isset( $attributes['borderWidth'] ) && $attributes['borderWidth'] > 0 ) {
			$styles['border-width'] = $attributes['borderWidth'] . 'px';
			$styles['border-style'] = 'solid';

			if ( ! empty( $attributes['borderColor'] ) ) {
				$color = $this->sanitize_css_color( $attributes['borderColor'] );
				if ( $color ) {
					$styles['border-color'] = $color;
				}
			}
		}

		if ( isset( $attributes['borderRadius'] ) && $attributes['borderRadius'] > 0 ) {
			$styles['border-radius'] = $attributes['borderRadius'] . 'px';
		}

		// Box shadow.
		if ( ! empty( $attributes['boxShadow'] ) ) {
			$h_offset = isset( $attributes['boxShadowHorizontal'] ) ? (int) $attributes['boxShadowHorizontal'] : 0;
			$v_offset = isset( $attributes['boxShadowVertical'] ) ? (int) $attributes['boxShadowVertical'] : 4;
			$blur     = isset( $attributes['boxShadowBlur'] ) ? (int) $attributes['boxShadowBlur'] : 8;
			$spread   = isset( $attributes['boxShadowSpread'] ) ? (int) $attributes['boxShadowSpread'] : 0;
			$raw      = ! empty( $attributes['boxShadowColor'] ) ? $attributes['boxShadowColor'] : 'rgba(0,0,0,0.2)';
			$color    = $this->sanitize_css_color( $raw ) ?: 'rgba(0,0,0,0.2)';

			$styles['box-shadow'] = $h_offset . 'px ' . $v_offset . 'px ' . $blur . 'px ' . $spread . 'px ' . $color;
		}

		// Section spacing custom property.
		if ( isset( $attributes['sectionSpacing'] ) ) {
			$styles['--author-section-spacing'] = $attributes['sectionSpacing'] . 'px';
		}

		// Animation duration.
		if ( isset( $attributes['animationDuration'] ) ) {
			$styles['--author-animation-duration'] = $attributes['animationDuration'] . 'ms';
		}

		// Transform properties.
		if ( isset( $attributes['transformScale'] ) && $attributes['transformScale'] !== 1 ) {
			$styles['--author-transform-scale'] = $attributes['transformScale'];
		}

		if ( isset( $attributes['transformRotate'] ) && $attributes['transformRotate'] !== 0 ) {
			$styles['--author-transform-rotate'] = $attributes['transformRotate'] . 'deg';
		}

		// Filter properties.
		if ( isset( $attributes['filterBrightness'] ) && $attributes['filterBrightness'] !== 100 ) {
			$styles['--author-filter-brightness'] = $attributes['filterBrightness'] . '%';
		}

		if ( isset( $attributes['filterContrast'] ) && $attributes['filterContrast'] !== 100 ) {
			$styles['--author-filter-contrast'] = $attributes['filterContrast'] . '%';
		}

		if ( isset( $attributes['filterSaturate'] ) && $attributes['filterSaturate'] !== 100 ) {
			$styles['--author-filter-saturate'] = $attributes['filterSaturate'] . '%';
		}

		// Gradient background.
		if ( ! empty( $attributes['gradientBackground'] ) ) {
			$start_color = $this->sanitize_css_color( $attributes['gradientStartColor'] ?? '#ffffff' ) ?: '#ffffff';
			$end_color   = $this->sanitize_css_color( $attributes['gradientEndColor'] ?? '#f8f9fa' ) ?: '#f8f9fa';
			$direction   = $this->sanitize_gradient_direction( $attributes['gradientDirection'] ?? 'to bottom' );

			$styles['background'] = 'linear-gradient(' . $direction . ', ' . $start_color . ', ' . $end_color . ')';
		}

		// Avatar custom properties.
		if ( isset( $attributes['avatarSize'] ) ) {
			$styles['--author-avatar-size'] = $attributes['avatarSize'] . 'px';
		}

		if ( isset( $attributes['avatarBorderWidth'] ) ) {
			$styles['--author-avatar-border-width'] = $attributes['avatarBorderWidth'] . 'px';
		}

		if ( ! empty( $attributes['avatarBorderColor'] ) ) {
			$color = $this->sanitize_css_color( $attributes['avatarBorderColor'] );
			if ( $color ) {
				$styles['--author-avatar-border-color'] = $color;
			}
		}

		if ( ! empty( $attributes['avatarShape'] ) && $attributes['avatarShape'] === 'custom' && isset( $attributes['avatarBorderRadius'] ) ) {
			$styles['--author-avatar-border-radius'] = $attributes['avatarBorderRadius'] . 'px';
		}

		if ( ! empty( $attributes['avatarAlignment'] ) ) {
			$styles['--author-avatar-align']   = $attributes['avatarAlignment'];
			$flex_justify                      = array(
				'left'   => 'flex-start',
				'center' => 'center',
				'right'  => 'flex-end',
			);
			$styles['--author-avatar-justify'] = $flex_justify[ $attributes['avatarAlignment'] ] ?? 'flex-start';
		}

		if ( isset( $attributes['avatarMargin'] ) ) {
			$styles['--author-avatar-margin'] = $attributes['avatarMargin'] . 'px';
		}

		// Name custom properties.
		if ( isset( $attributes['nameSize'] ) ) {
			$styles['--author-name-size'] = $attributes['nameSize'] . 'px';
		}

		if ( ! empty( $attributes['nameWeight'] ) ) {
			$styles['--author-name-weight'] = $attributes['nameWeight'];
		}

		if ( ! empty( $attributes['nameColor'] ) ) {
			$color = $this->sanitize_css_color( $attributes['nameColor'] );
			if ( $color ) {
				$styles['--author-name-color'] = $color;
			}
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

		// Description custom properties.
		if ( isset( $attributes['descriptionSize'] ) ) {
			$styles['--author-description-size'] = $attributes['descriptionSize'] . 'px';
		}

		if ( isset( $attributes['descriptionLineHeight'] ) ) {
			$styles['--author-description-line-height'] = $attributes['descriptionLineHeight'];
		}

		if ( ! empty( $attributes['descriptionColor'] ) ) {
			$color = $this->sanitize_css_color( $attributes['descriptionColor'] );
			if ( $color ) {
				$styles['--author-description-color'] = $color;
			}
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

		// Meta custom properties.
		if ( isset( $attributes['metaSize'] ) ) {
			$styles['--author-meta-size'] = $attributes['metaSize'] . 'px';
		}

		if ( ! empty( $attributes['metaColor'] ) ) {
			$color = $this->sanitize_css_color( $attributes['metaColor'] );
			if ( $color ) {
				$styles['--author-meta-color'] = $color;
			}
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

		// Email link custom properties.
		if ( ! empty( $attributes['emailLinkColor'] ) ) {
			$color = $this->sanitize_css_color( $attributes['emailLinkColor'] );
			if ( $color ) {
				$styles['--author-email-link-color'] = $color;
			}
		}

		if ( ! empty( $attributes['emailHoverColor'] ) ) {
			$color = $this->sanitize_css_color( $attributes['emailHoverColor'] );
			if ( $color ) {
				$styles['--author-email-link-hover-color'] = $color;
			}
		}

		// Social icon custom properties.
		if ( isset( $attributes['socialIconSize'] ) ) {
			$styles['--author-social-icon-size'] = $attributes['socialIconSize'] . 'px';
		}

		if ( ! empty( $attributes['socialIconColor'] ) ) {
			$color = $this->sanitize_css_color( $attributes['socialIconColor'] );
			if ( $color ) {
				$styles['--author-social-icon-color'] = $color;
			}
		}

		if ( ! empty( $attributes['socialIconHoverColor'] ) ) {
			$color = $this->sanitize_css_color( $attributes['socialIconHoverColor'] );
			if ( $color ) {
				$styles['--author-social-icon-hover-color'] = $color;
			}
		}

		if ( ! empty( $attributes['socialIconBackground'] ) ) {
			$color = $this->sanitize_css_color( $attributes['socialIconBackground'] );
			if ( $color ) {
				$styles['--author-social-icon-bg'] = $color;
			}
		}

		if ( ! empty( $attributes['socialIconBackgroundHover'] ) ) {
			$color = $this->sanitize_css_color( $attributes['socialIconBackgroundHover'] );
			if ( $color ) {
				$styles['--author-social-icon-bg-hover'] = $color;
			}
		}

		if ( isset( $attributes['socialIconSpacing'] ) ) {
			$styles['--author-social-icon-spacing'] = $attributes['socialIconSpacing'] . 'px';
		}

		if ( ! empty( $attributes['socialIconAlignment'] ) ) {
			$styles['--author-social-icon-align'] = $attributes['socialIconAlignment'];
		}

		// More content custom properties.
		if ( ! empty( $attributes['moreContentBorderColor'] ) ) {
			$color = $this->sanitize_css_color( $attributes['moreContentBorderColor'] );
			if ( $color ) {
				$styles['--author-more-content-border-color'] = $color;
			}
		}

		if ( isset( $attributes['moreContentPadding'] ) ) {
			$styles['--author-more-content-padding'] = $attributes['moreContentPadding'] . 'px';
		}

		// Custom CSS variables.
		if ( ! empty( $attributes['customVar1'] ) ) {
			$styles['--author-profile-custom-var-1'] = sanitize_text_field( $attributes['customVar1'] );
		}

		if ( ! empty( $attributes['customVar2'] ) ) {
			$styles['--author-profile-custom-var-2'] = sanitize_text_field( $attributes['customVar2'] );
		}

		// Border color if enabled.
		if ( ! empty( $attributes['enableBorder'] ) && ! empty( $attributes['borderColor'] ) ) {
			$color = $this->sanitize_css_color( $attributes['borderColor'] );
			if ( $color ) {
				$styles['border-color'] = $color;
			}
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
	 * @return array<string, string> CSS styles as associative array.
	 */
	protected function get_item_styles( array $attributes ): array {
		$styles = array();

		// Item background color.
		if ( ! empty( $attributes['itemBackgroundColor'] ) ) {
			$color = $this->sanitize_css_color( $attributes['itemBackgroundColor'] );
			if ( $color ) {
				$styles['background-color'] = $color;
			}
		}

		// Padding.
		if ( isset( $attributes['itemPadding'] ) ) {
			$styles['padding'] = $attributes['itemPadding'] . 'px';
		}

		// Border color if dividers enabled.
		if ( ! empty( $attributes['enableDividers'] ) && ! empty( $attributes['dividerColor'] ) ) {
			$color = $this->sanitize_css_color( $attributes['dividerColor'] );
			if ( $color ) {
				$styles['border-color'] = $color;
			}
		}

		// Animation duration for items.
		if ( isset( $attributes['animationDuration'] ) ) {
			$styles['animation-duration'] = $attributes['animationDuration'] . 'ms';
		}

		// Transform scale.
		if ( isset( $attributes['transformScale'] ) && $attributes['transformScale'] !== 1 ) {
			$styles['transform'] = 'scale(' . $attributes['transformScale'] . ')';
		}

		// Transform rotate.
		if ( isset( $attributes['transformRotate'] ) && $attributes['transformRotate'] !== 0 ) {
			$current_transform   = $styles['transform'] ?? '';
			$rotate_transform    = 'rotate(' . $attributes['transformRotate'] . 'deg)';
			$styles['transform'] = $current_transform ? $current_transform . ' ' . $rotate_transform : $rotate_transform;
		}

		// Filter properties.
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
	 * @param array<string, string> $styles Array of CSS property:value pairs.
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

	/**
	 * Sanitize a CSS color value.
	 * Accepts hex (#rgb, #rrggbb, 3/4/6/8-digit), rgb(), rgba(), hsl(), hsla(),
	 * and safe CSS keywords. Returns empty string for invalid values.
	 *
	 * @param string $color Raw color value.
	 * @return string Sanitized color or empty string.
	 */
	private function sanitize_css_color( string $color ): string {
		$color = trim( $color );
		if ( '' === $color ) {
			return '';
		}

		// Hex colors via WP built-in (handles #rgb and #rrggbb).
		$hex = sanitize_hex_color( $color );
		if ( null !== $hex ) {
			return $hex;
		}

		// 4- and 8-digit hex (#rgba / #rrggbbaa) not covered by sanitize_hex_color.
		if ( preg_match( '/^#([0-9a-fA-F]{4}|[0-9a-fA-F]{8})$/', $color ) ) {
			return $color;
		}

		// Safe CSS color keywords.
		$keywords = array( 'transparent', 'currentcolor', 'inherit', 'initial', 'unset' );
		if ( in_array( strtolower( $color ), $keywords, true ) ) {
			return strtolower( $color );
		}

		// rgb() / rgba() / hsl() / hsla() — strip to safe characters only.
		if ( preg_match( '/^(rgba?|hsla?)\s*\([^)]*\)$/i', $color ) ) {
			return preg_replace( '/[^a-zA-Z0-9(),%. \/\-]/', '', $color );
		}

		return '';
	}

	/**
	 * Sanitize a CSS length value (e.g. 100px, 50%, 2rem).
	 * Returns empty string for values that don't match a known pattern.
	 *
	 * @param string $value Raw length value.
	 * @return string Sanitized length or empty string.
	 */
	private function sanitize_css_length( string $value ): string {
		$value = trim( $value );
		if ( preg_match( '/^\d+(\.\d+)?(px|em|rem|%|vw|vh|vmin|vmax|ch|ex|cm|mm|in|pt|pc)?$/', $value ) ) {
			return $value;
		}
		return '';
	}

	/**
	 * Sanitize a CSS gradient direction.
	 * Accepts 'to [side]', 'to [corner]', and angle values (e.g. '45deg').
	 * Falls back to 'to bottom' for unrecognised values.
	 *
	 * @param string $direction Raw gradient direction.
	 * @return string Sanitized direction.
	 */
	private function sanitize_gradient_direction( string $direction ): string {
		$direction = trim( $direction );
		if ( preg_match( '/^(to (top|bottom|left|right)( (top|bottom|left|right))?|\d+(\.\d+)?deg)$/', $direction ) ) {
			return $direction;
		}
		return 'to bottom';
	}
}
