<?php
/**
 * Author Profile Block class
 *
 * @package WPAuthorShowcase
 * @subpackage Blocks
 */

namespace WPAuthorShowcase\Blocks;

use WP_Block;
use WP_Post;
use WPAuthorShowcase\Post_Types\Author_Profile_CPT;
use WPAuthorShowcase\Plugin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles the Author Profile block.
 */
class Author_Profile_Block extends Block_Base {
	/**
	 * Get the block name.
	 *
	 * @return string Block name.
	 */
	protected function get_block_name(): string {
		return 'author-profile';
	}

	/**
	 * Additional initialization actions for the block.
	 *
	 * @return void
	 */
	protected function additional_init(): void {
		// Localize script with admin URL for the editor
		add_action('enqueue_block_editor_assets', array($this, 'localize_block_script'));
	}

	/**
	 * Localize block script with necessary data
	 *
	 * @return void
	 */
	public function localize_block_script(): void {
		wp_localize_script(
			'wp-author-showcase-author-profile-editor-script',
			'wpAuthorShowcaseData',
			array(
				'adminUrl' => admin_url(),
			)
		);
	}

	/**
	 * Get render callback for the block.
	 *
	 * @return callable|null Block render callback.
	 */
	protected function get_render_callback(): ?callable {
		return array( $this, 'render_callback' );
	}

	/**
	 * Render callback for the block.
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content    Block content.
	 * @param WP_Block $block      Block instance.
	 * @return string Rendered block output.
	 */
	public function render_callback( array $attributes, string $content, $block ): string {
		$author_id = $attributes['authorId'] ?? 0;

		if ( empty( $author_id ) ) {
			return '<div class="wpas-author-profile-error">' . esc_html__('Please select an author.', 'wp-author-showcase') . '</div>';
		}

		// Get the Author_Profile_CPT instance to use its methods
		$author_cpt = Plugin::get_instance()->get_author_profile_cpt();
		$author_data = $author_cpt->get_author_data($author_id);

		if ( ! $author_data ) {
			return '<div class="wpas-author-profile-error">' . esc_html__('Author not found.', 'wp-author-showcase') . '</div>';
		}

		// Generate styles for the block.
		$styles = $this->get_block_styles($attributes);
		$style_attribute = '';

		if (!empty($styles)) {
			$style_strings = array();
			foreach ($styles as $property => $value) {
				$style_strings[] = $property . ': ' . $value;
			}
			$style_attribute = 'style="' . esc_attr(implode('; ', $style_strings)) . '"';
		}

		// Classes for the block wrapper.
		$wrapper_attributes = get_block_wrapper_attributes(
			array(
				'class' => $this->get_block_classes( $attributes ),
				'style' => $style_attribute,
			)
		);

		// Build the HTML.
		$html  = '<div ' . $wrapper_attributes . '>';
		$html .= $this->render_author_content( $author_data, $attributes );

		// Add optional more content if enabled.
		if ( ! empty( $attributes['showMoreContent'] ) && ! empty( $attributes['moreContent'] ) ) {
			$html .= $this->render_more_content( $attributes['moreContent'] );
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Get block classes based on attributes.
	 *
	 * @param array $attributes Block attributes.
	 * @return string CSS classes.
	 */
	private function get_block_classes( array $attributes ): string {
		$classes = array();

		// Text alignment.
		if ( ! empty( $attributes['textAlign'] ) ) {
			$classes[] = 'has-text-align-' . $attributes['textAlign'];
		}

		return implode( ' ', $classes );
	}

	/**
	 * Get block styles based on attributes.
	 *
	 * @param array $attributes Block attributes.
	 * @return array CSS styles.
	 */
	private function get_block_styles( array $attributes ): array {
		$styles = array();

		// Background color
		if ( ! empty( $attributes['backgroundColor'] ) ) {
			$styles['background-color'] = $attributes['backgroundColor'];
		}

		// Padding
		if ( isset( $attributes['padding'] ) ) {
			$styles['padding'] = $attributes['padding'] . 'px';
		}

		return $styles;
	}

	/**
	 * Render author content.
	 *
	 * @param array $author Author data.
	 * @param array $attributes Block attributes.
	 * @return string Rendered HTML.
	 */
	private function render_author_content( array $author, array $attributes ): string {
		$html = '<div class="wpas-author-profile-content">';

		// Author image - only if image display is enabled in attributes
		if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) {
			$html .= '<div class="wpas-author-image">';
			$html .= '<img src="' . esc_url( $author['image'] ) . '" alt="' . esc_attr( $author['title'] ) . '" />';
			$html .= '</div>';
		}

		// Author info.
		$html .= '<div class="wpas-author-info">';

		// Author name.
		if ( ! empty( $author['title'] ) ) {
			$html .= '<h3 class="wpas-author-name">' . esc_html( $author['title'] ) . '</h3>';
		}

		// Author email - only if email display is enabled in attributes
		if ( ! empty( $author['email'] ) && ( ! isset( $attributes['showEmail'] ) || $attributes['showEmail'] ) ) {
			$html .= '<div class="wpas-author-email">';
			$html .= '<a href="mailto:' . esc_attr( $author['email'] ) . '">' . esc_html( $author['email'] ) . '</a>';
			$html .= '</div>';
		}

		// Author description - only if description display is enabled in attributes
		if ( ! empty( $author['description'] ) && ( ! isset( $attributes['showDescription'] ) || $attributes['showDescription'] ) ) {
			$html .= '<div class="wpas-author-description">';
			$html .= wp_kses_post( $author['description'] );
			$html .= '</div>';
		}

		$html .= '</div>'; // Close .wpas-author-info
		$html .= '</div>'; // Close .wpas-author-profile-content

		return $html;
	}

	/**
	 * Render more content section.
	 *
	 * @param string $content The content.
	 * @return string Rendered HTML.
	 */
	private function render_more_content( string $content ): string {
		return '<div class="wpas-author-more-content">' . wp_kses_post( $content ) . '</div>';
	}
}
