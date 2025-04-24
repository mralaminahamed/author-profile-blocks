<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Renderer Trait
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

		return '<div class="' . esc_attr( $classes ) . '">' .
				'<img src="' . esc_url( $author['image'] ) . '" alt="' .
				esc_attr( $author['title'] ) . '" loading="lazy" />' .
				'</div>';
	}

	/**
	 * Render author name section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_name( array $author ): string {
		return '<h3 class="apb-author-name">' . esc_html( $author['title'] ) . '</h3>';
	}

	/**
	 * Render author position section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_position( array $author ): string {
		return '<div class="apb-author-position">' . esc_html( $author['position'] ) . '</div>';
	}

	/**
	 * Render author email section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_email( array $author ): string {
		return '<div class="apb-author-email">' .
				'<a href="mailto:' . esc_attr( $author['email'] ) . '">' .
				esc_html( $author['email'] ) . '</a>' .
				'</div>';
	}

	/**
	 * Render author description section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_description( array $author ): string {
		return '<div class="apb-author-description">' .
				wp_kses_post( $author['description'] ) .
				'</div>';
	}

	/**
	 * Render social profiles section.
	 *
	 * @param array  $profiles      Social profile URLs.
	 * @param string $wrapper_class Optional. Additional CSS class for the social profiles container.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_social_profiles( array $profiles, string $wrapper_class = '' ): string {
		$classes = 'apb-social-profiles';
		if ( ! empty( $wrapper_class ) ) {
			$classes .= ' ' . $wrapper_class;
		}

		$html  = '<div class="' . esc_attr( $classes ) . '">';
		$html .= '<ul class="apb-social-list">';

		$social_icons = $this->get_social_icons();

		foreach ( $profiles as $network => $url ) {
			if ( ! empty( $url ) && isset( $social_icons[ $network ] ) ) {
				$html .= '<li class="apb-social-item apb-social-' . esc_attr( $network ) . '">';
				$html .= '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">';
				$html .= '<span class="dashicons ' . esc_attr( $social_icons[ $network ] ) . '" aria-hidden="true"></span>';
				$html .= '<span class="screen-reader-text">' . esc_html( ucfirst( $network ) ) . '</span>';
				$html .= '</a>';
				$html .= '</li>';
			}
		}

		$html .= '</ul>';
		$html .= '</div>';

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

		return '<div class="apb-author-registered-date">' .
				'<span class="apb-registered-date-label">' . esc_html( $label ) . '</span> ' .
				'<span class="apb-registered-date-value">' . esc_html( $author['registered_date'] ) . '</span>' .
				'</div>';
	}

	/**
	 * Render more content section.
	 *
	 * @param string $content The content.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_more_content( string $content ): string {
		return '<div class="apb-author-more-content">' . wp_kses_post( $content ) . '</div>';
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
				'name' => 'Facebook',
				'icon' => 'facebook',
			),
			'twitter'   => array(
				'name' => 'Twitter',
				'icon' => 'twitter',
			),
			'linkedin'  => array(
				'name' => 'LinkedIn',
				'icon' => 'linkedin',
			),
			'instagram' => array(
				'name' => 'Instagram',
				'icon' => 'instagram',
			),
			'website'   => array(
				'name' => 'Website',
				'icon' => 'admin-site',
			),
		);
	}
}
