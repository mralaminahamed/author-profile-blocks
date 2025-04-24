<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Renderer Trait
 *
 * @package AuthorProfileBlocks
 */

namespace AuthorProfileBlocks\Common;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait for rendering author profile elements consistently across blocks.
 */
trait Author_Renderer {
	/**
	 * Render author image section.
	 *
	 * @param array  $author        Author data.
	 * @param string $wrapper_class Optional. Additional CSS class for the image container.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_image( array $author, string $wrapper_class = '' ): string {
		$classes = 'apb-author-image';
		if ( ! empty( $wrapper_class ) ) {
			$classes .= ' ' . $wrapper_class;
		}
		
		// Add alignment to the container class if specified
		if ( !empty( $author['avatarAlignment'] ) ) {
			$classes .= ' apb-author-image-align-' . esc_attr( $author['avatarAlignment'] );
		}

		$image_classes = array();
		
		// Add avatar shape class if available from attributes
		if ( !empty( $author['avatarShape'] ) ) {
			$image_classes[] = 'avatar-shape-' . esc_attr( $author['avatarShape'] );
		}
		
		// Build custom CSS for the avatar
		$avatar_styles = $this->get_avatar_inline_styles( $author );
		
		$image_attr = array(
			'class'   => !empty( $image_classes ) ? implode( ' ', $image_classes ) : '',
			'alt'     => esc_attr( $author['title'] ),
			'loading' => 'lazy',
			'style'   => $avatar_styles,
		);

		return '<div class="' . esc_attr( $classes ) . '">' . 
			wp_get_attachment_image(
				$author['image'],
				'full',
				false,
				$image_attr
			) . 
		'</div>';
	}
	
	/**
	 * Generate inline styles for avatar image
	 *
	 * @param array $author Author data with style attributes
	 * 
	 * @return string CSS inline style string
	 */
	private function get_avatar_inline_styles( array $author ): string {
		$styles = array();
		
		// Size
		if ( !empty( $author['avatarSize'] ) ) {
			$styles[] = 'width: ' . esc_attr( $author['avatarSize'] ) . 'px';
			$styles[] = 'height: ' . esc_attr( $author['avatarSize'] ) . 'px';
		}
		
		// Border
		if ( !empty( $author['avatarBorderWidth'] ) && $author['avatarBorderWidth'] > 0 ) {
			$styles[] = 'border-width: ' . esc_attr( $author['avatarBorderWidth'] ) . 'px';
			$styles[] = 'border-style: solid';
			
			if ( !empty( $author['avatarBorderColor'] ) ) {
				$styles[] = 'border-color: ' . esc_attr( $author['avatarBorderColor'] );
			}
		}
		
		// Custom border radius for custom shape
		if ( !empty( $author['avatarShape'] ) && $author['avatarShape'] === 'custom' && !empty( $author['avatarBorderRadius'] ) ) {
			$styles[] = 'border-radius: ' . esc_attr( $author['avatarBorderRadius'] ) . 'px';
		} elseif ( !empty( $author['avatarShape'] ) && $author['avatarShape'] === 'circle' ) {
			$styles[] = 'border-radius: 50%';
		} elseif ( !empty( $author['avatarShape'] ) && $author['avatarShape'] === 'rounded' ) {
			$styles[] = 'border-radius: 8px';
		}
		
		// Margin
		if ( !empty( $author['avatarMargin'] ) ) {
			$styles[] = 'margin-bottom: ' . esc_attr( $author['avatarMargin'] ) . 'px';
		}
		
		// Object fit to ensure proper sizing
		$styles[] = 'object-fit: cover';
		
		return implode( '; ', $styles );
	}

	/**
	 * Render author name section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_name( array $author ): string {
		$style_attr = $this->get_name_inline_styles( $author );
		$style_html = !empty( $style_attr ) ? ' style="' . $style_attr . '"' : '';
		
		// Build the class attribute with alignment if specified
		$class_attr = 'apb-author-name';
		if ( !empty( $author['nameAlignment'] ) ) {
			$class_attr .= ' has-text-align-' . esc_attr( $author['nameAlignment'] );
		}
		
		if ( isset( $author['url'] ) && '' !== $author['url'] ) {
			return sprintf(
				'<h3 class="%s"%s><a href="%s"%s>%s</a></h3>',
				esc_attr( $class_attr ),
				$style_html,
				esc_url( $author['url'] ),
				!empty( $style_attr ) ? ' style="color: inherit; text-decoration: none;"' : '',
				esc_html( $author['title'] )
			);
		}

		return sprintf(
			'<h3 class="%s"%s>%s</h3>',
			esc_attr( $class_attr ),
			$style_html,
			esc_html( $author['title'] )
		);
	}
	
	/**
	 * Generate inline styles for author name
	 *
	 * @param array $author Author data with style attributes
	 * 
	 * @return string CSS inline style string
	 */
	private function get_name_inline_styles( array $author ): string {
		$styles = array();
		
		// Font size
		if ( !empty( $author['nameSize'] ) ) {
			$styles[] = 'font-size: ' . esc_attr( $author['nameSize'] ) . 'px';
		}
		
		// Font weight
		if ( !empty( $author['nameWeight'] ) ) {
			$styles[] = 'font-weight: ' . esc_attr( $author['nameWeight'] );
		}
		
		// Text color
		if ( !empty( $author['nameColor'] ) ) {
			$styles[] = 'color: ' . esc_attr( $author['nameColor'] );
		}
		
		// Text transform
		if ( !empty( $author['nameTransform'] ) && $author['nameTransform'] !== 'none' ) {
			$styles[] = 'text-transform: ' . esc_attr( $author['nameTransform'] );
		}
		
		// Text alignment
		if ( !empty( $author['nameAlignment'] ) ) {
			$styles[] = 'text-align: ' . esc_attr( $author['nameAlignment'] );
		}
		
		// Margin
		if ( !empty( $author['nameMargin'] ) ) {
			$styles[] = 'margin-bottom: ' . esc_attr( $author['nameMargin'] ) . 'px';
		}
		
		return implode( '; ', $styles );
	}

	/**
	 * Render author position section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_position( array $author ): string {
		$style_attr = $this->get_meta_inline_styles( $author );
		$style_html = !empty( $style_attr ) ? ' style="' . $style_attr . '"' : '';
		
		// Build class attribute with alignment if specified
		$class_attr = 'apb-author-position';
		if ( !empty( $author['metaAlignment'] ) ) {
			$class_attr .= ' has-text-align-' . esc_attr( $author['metaAlignment'] );
		}
		
		return sprintf(
			'<div class="%s"%s>%s</div>',
			esc_attr( $class_attr ),
			$style_html,
			esc_html( $author['position'] )
		);
	}

	/**
	 * Render author email section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_email( array $author ): string {
		$style_attr = $this->get_meta_inline_styles( $author );
		$style_html = !empty( $style_attr ) ? ' style="' . $style_attr . '"' : '';
		
		// Build class attribute with alignment if specified
		$class_attr = 'apb-author-email';
		if ( !empty( $author['metaAlignment'] ) ) {
			$class_attr .= ' has-text-align-' . esc_attr( $author['metaAlignment'] );
		}
		
		// Generate email link style
		$link_styles = array();
		
		if ( !empty( $author['emailLinkColor'] ) ) {
			$link_styles[] = 'color: ' . esc_attr( $author['emailLinkColor'] );
		}
		
		$link_style_html = !empty( $link_styles ) ? ' style="' . implode( '; ', $link_styles ) . '"' : '';
		
		// Add hover style via data attribute which will be handled by CSS
		$data_attr = '';
		if ( !empty( $author['emailHoverColor'] ) ) {
			$data_attr = ' data-hover-color="' . esc_attr( $author['emailHoverColor'] ) . '"';
		}
		
		return sprintf(
			'<div class="%s"%s><a href="mailto:%s"%s%s>%s</a></div>',
			esc_attr( $class_attr ),
			$style_html,
			esc_attr( $author['email'] ),
			$link_style_html,
			$data_attr,
			esc_html( $author['email'] )
		);
	}
	
	/**
	 * Generate inline styles for meta elements (email, registered date)
	 *
	 * @param array $author Author data with style attributes
	 * 
	 * @return string CSS inline style string
	 */
	private function get_meta_inline_styles( array $author ): string {
		$styles = array();
		
		// Font size
		if ( !empty( $author['metaSize'] ) ) {
			$styles[] = 'font-size: ' . esc_attr( $author['metaSize'] ) . 'px';
		}
		
		// Text color
		if ( !empty( $author['metaColor'] ) ) {
			$styles[] = 'color: ' . esc_attr( $author['metaColor'] );
		}
		
		// Font style
		if ( !empty( $author['metaStyle'] ) && $author['metaStyle'] !== 'normal' ) {
			$styles[] = 'font-style: ' . esc_attr( $author['metaStyle'] );
		}
		
		// Font weight
		if ( !empty( $author['metaBold'] ) && $author['metaBold'] ) {
			$styles[] = 'font-weight: bold';
		}
		
		// Text alignment
		if ( !empty( $author['metaAlignment'] ) ) {
			$styles[] = 'text-align: ' . esc_attr( $author['metaAlignment'] );
		}
		
		// Margin
		if ( !empty( $author['metaMargin'] ) ) {
			$styles[] = 'margin-bottom: ' . esc_attr( $author['metaMargin'] ) . 'px';
		}
		
		return implode( '; ', $styles );
	}

	/**
	 * Render author description section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_description( array $author ): string {
		$style_attr = $this->get_description_inline_styles( $author );
		$style_html = !empty( $style_attr ) ? ' style="' . $style_attr . '"' : '';
		
		// Build class attribute with alignment if specified
		$class_attr = 'apb-author-description';
		if ( !empty( $author['descriptionAlignment'] ) ) {
			$class_attr .= ' has-text-align-' . esc_attr( $author['descriptionAlignment'] );
		}
		
		return sprintf(
			'<div class="%s"%s>%s</div>',
			esc_attr( $class_attr ),
			$style_html,
			wp_kses_post( $author['description'] )
		);
	}
	
	/**
	 * Generate inline styles for author description
	 *
	 * @param array $author Author data with style attributes
	 * 
	 * @return string CSS inline style string
	 */
	private function get_description_inline_styles( array $author ): string {
		$styles = array();
		
		// Font size
		if ( !empty( $author['descriptionSize'] ) ) {
			$styles[] = 'font-size: ' . esc_attr( $author['descriptionSize'] ) . 'px';
		}
		
		// Line height
		if ( !empty( $author['descriptionLineHeight'] ) ) {
			$styles[] = 'line-height: ' . esc_attr( $author['descriptionLineHeight'] );
		}
		
		// Text color
		if ( !empty( $author['descriptionColor'] ) ) {
			$styles[] = 'color: ' . esc_attr( $author['descriptionColor'] );
		}
		
		// Font style
		if ( !empty( $author['descriptionStyle'] ) && $author['descriptionStyle'] !== 'normal' ) {
			$styles[] = 'font-style: ' . esc_attr( $author['descriptionStyle'] );
		}
		
		// Text alignment
		if ( !empty( $author['descriptionAlignment'] ) ) {
			$styles[] = 'text-align: ' . esc_attr( $author['descriptionAlignment'] );
		}
		
		// Margin
		if ( !empty( $author['descriptionMargin'] ) ) {
			$styles[] = 'margin-bottom: ' . esc_attr( $author['descriptionMargin'] ) . 'px';
		}
		
		return implode( '; ', $styles );
	}

	/**
	 * Render social profiles section.
	 *
	 * @param array  $profiles      Social profile URLs.
	 * @param string $wrapper_class Optional. Additional CSS class for the social profiles container.
	 * @param array  $show_profiles Optional. List of specific profiles to show.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_social_profiles( array $profiles, string $wrapper_class = '', array $show_profiles = array() ): string {
		$classes = 'apb-social-profiles';
		if ( ! empty( $wrapper_class ) ) {
			$classes .= ' ' . $wrapper_class;
		}
		
		// Get social icon alignment if available
		if ( !empty( $profiles['socialIconAlignment'] ) ) {
			$classes .= ' apb-social-align-' . esc_attr( $profiles['socialIconAlignment'] );
			$data_align = ' data-align="' . esc_attr( $profiles['socialIconAlignment'] ) . '"';
		} else {
			$data_align = '';
		}
		
		// Generate styles for icons
		$icon_styles = array();
		$icon_hover_styles = array();
		
		if ( !empty( $profiles['socialIconSize'] ) ) {
			$icon_styles[] = '--author-social-icon-size: ' . esc_attr( $profiles['socialIconSize'] ) . 'px';
		}
		
		if ( !empty( $profiles['socialIconColor'] ) ) {
			$icon_styles[] = '--author-social-icon-color: ' . esc_attr( $profiles['socialIconColor'] );
		}
		
		if ( !empty( $profiles['socialIconHoverColor'] ) ) {
			$icon_hover_styles[] = '--author-social-icon-hover-color: ' . esc_attr( $profiles['socialIconHoverColor'] );
		}
		
		if ( !empty( $profiles['socialIconBackground'] ) ) {
			$icon_styles[] = '--author-social-icon-bg: ' . esc_attr( $profiles['socialIconBackground'] );
		}
		
		if ( !empty( $profiles['socialIconBackgroundHover'] ) ) {
			$icon_hover_styles[] = '--author-social-icon-bg-hover: ' . esc_attr( $profiles['socialIconBackgroundHover'] );
		}
		
		if ( !empty( $profiles['socialIconSpacing'] ) ) {
			$icon_styles[] = '--author-social-icon-spacing: ' . esc_attr( $profiles['socialIconSpacing'] ) . 'px';
		}
		
		// Build style attribute
		$style_html = !empty( $icon_styles ) ? ' style="' . implode( '; ', $icon_styles ) . '"' : '';
		$data_hover = !empty( $icon_hover_styles ) ? ' data-hover-style="' . implode( '; ', $icon_hover_styles ) . '"' : '';
		
		$html = '<div class="' . esc_attr( $classes ) . '"' . $data_align . $style_html . $data_hover . '>';
		$html .= '<ul class="apb-social-list">';

		$social_icons = $this->get_social_icons();
		
		// If specific profiles are specified, only show those
		$filtered_profiles = array();
		if (!empty($show_profiles)) {
			foreach ($profiles as $network => $url) {
				if ($network !== 'socialIconSize' && 
					$network !== 'socialIconColor' && 
					$network !== 'socialIconHoverColor' && 
					$network !== 'socialIconBackground' && 
					$network !== 'socialIconBackgroundHover' && 
					$network !== 'socialIconSpacing' && 
					$network !== 'socialIconAlignment' && 
					in_array($network, $show_profiles, true)) {
					$filtered_profiles[$network] = $url;
				}
			}
		} else {
			// Filter out the style properties from profiles
			foreach ($profiles as $network => $url) {
				if ($network !== 'socialIconSize' && 
					$network !== 'socialIconColor' && 
					$network !== 'socialIconHoverColor' && 
					$network !== 'socialIconBackground' && 
					$network !== 'socialIconBackgroundHover' && 
					$network !== 'socialIconSpacing' && 
					$network !== 'socialIconAlignment') {
					$filtered_profiles[$network] = $url;
				}
			}
		}

		foreach ( $filtered_profiles as $network => $url ) {
			if ( ! empty( $url ) && isset( $social_icons[ $network ] ) ) {
				$html .= '<li class="apb-social-item apb-social-' . esc_attr( $network ) . '">';
				$html .= '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">';
				$html .= '<span class="dashicons ' . esc_attr( $social_icons[ $network ] ) . '" aria-hidden="true"></span>';
				$html .= '<span class="screen-reader-text">' . esc_html( ucfirst( $network ) ) . '</span>';
				$html .= '</a>';
				$html .= '</li>';
			}
		}

		$html .= '</ul></div>';

		return $html;
	}

	/**
	 * Render registered date section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_registered_date( array $author ): string {
		// Use the customizable label.
		$label = $author['member_since_label'] ?? __( 'Member since', 'author-profile-blocks' );
		
		$style_attr = $this->get_meta_inline_styles( $author );
		$style_html = !empty( $style_attr ) ? ' style="' . $style_attr . '"' : '';
		
		// Build class attribute with alignment if specified
		$class_attr = 'apb-author-registered-date';
		if ( !empty( $author['metaAlignment'] ) ) {
			$class_attr .= ' has-text-align-' . esc_attr( $author['metaAlignment'] );
		}

		return sprintf(
			'<div class="%s"%s><span class="apb-registered-date-label">%s</span> <span class="apb-registered-date-value">%s</span></div>',
			esc_attr( $class_attr ),
			$style_html,
			esc_html( $label ),
			esc_html( $author['registered_date'] )
		);
	}

	/**
	 * Render more content section.
	 *
	 * @param string $content The content.
	 * @param array  $author  Optional. Author data with style attributes.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_more_content( string $content, array $author = array() ): string {
		if ( '' === $content ) {
			return '';
		}
		
		$styles = array();
		
		// Add border color if specified
		if ( !empty( $author['moreContentBorderColor'] ) ) {
			$styles[] = 'border-top-color: ' . esc_attr( $author['moreContentBorderColor'] );
		}
		
		// Add top padding if specified
		if ( !empty( $author['moreContentPadding'] ) ) {
			$styles[] = 'padding-top: ' . esc_attr( $author['moreContentPadding'] ) . 'px';
		}
		
		$style_html = !empty( $styles ) ? ' style="' . implode( '; ', $styles ) . '"' : '';

		return sprintf(
			'<div class="apb-author-more-content"%s>%s</div>',
			$style_html,
			wp_kses_post( $content )
		);
	}

	/**
	 * Get social icon data.
	 *
	 * @return array Social icon data.
	 */
	protected function get_social_icons(): array {
		return array(
			'facebook'  => 'dashicons-facebook',
			'twitter'   => 'dashicons-twitter',
			'linkedin'  => 'dashicons-linkedin',
			'instagram' => 'dashicons-instagram',
			'website'   => 'dashicons-admin-site',
		);
	}

	/**
	 * Get social icon data for block editor.
	 *
	 * @return array Social icon data.
	 */
	protected function get_social_icon_data(): array {
		return array(
			'facebook'  => array(
				'name' => esc_html__( 'Facebook', 'author-profile-blocks' ),
				'icon' => 'facebook',
			),
			'twitter'   => array(
				'name' => esc_html__( 'Twitter', 'author-profile-blocks' ),
				'icon' => 'twitter',
			),
			'linkedin'  => array(
				'name' => esc_html__( 'LinkedIn', 'author-profile-blocks' ),
				'icon' => 'linkedin',
			),
			'instagram' => array(
				'name' => esc_html__( 'Instagram', 'author-profile-blocks' ),
				'icon' => 'instagram',
			),
			'website'   => array(
				'name' => esc_html__( 'Website', 'author-profile-blocks' ),
				'icon' => 'admin-site',
			),
		);
	}
}
