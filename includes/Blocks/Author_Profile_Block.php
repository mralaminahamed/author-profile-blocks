<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Profile Block class
 *
 * @package AuthorProfileBlocks
 */

namespace AuthorProfileBlocks\Blocks;

use AuthorProfileBlocks\Common\Author_Block_Base;
use WP_Block;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles the Author Profile block.
 */
class Author_Profile_Block extends Author_Block_Base {
	/**
	 * Get the block name.
	 *
	 * @return string Block name.
	 */
	public function get_block_name(): string {
		return 'author-profile';
	}

	/**
	 * Block-specific initialization.
	 *
	 * @return void
	 */
	protected function block_specific_init(): void {
		add_action( 'enqueue_block_editor_assets', array( $this, 'localize_block_script' ) );
	}

	/**
	 * Localize block script with necessary data
	 *
	 * @return void
	 */
	public function localize_block_script(): void {
		wp_localize_script(
			'author-profile-blocks-author-profile-editor-script',
			'AuthorProfileBlocksData',
			array(
				'adminUrl'    => admin_url(),
				'restNonce'   => wp_create_nonce( 'wp_rest' ),
				'restUrl'     => rest_url(),
				'pluginUrl'   => APB_PLUGIN_URL,
				'socialIcons' => $this->get_social_icon_data(),
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
	 *
	 * @return string Rendered block output.
	 */
	public function render_callback( array $attributes, string $content, $block ): string {
		$author_id = $attributes['authorId'] ?? 0;

		if ( empty( $author_id ) ) {
			return $this->render_error_message( __( 'Please select an author.', 'author-profile-blocks' ) );
		}

		// Check cache first.
		$cache_key      = $this->generate_cache_key( $author_id, $attributes );
		$cached_content = $this->get_cached_render( $cache_key );
		if ( $cached_content ) {
			return $cached_content;
		}

		// Get author data.
		$author_data = $this->get_author_data( $author_id );

		if ( ! $author_data ) {
			return $this->render_error_message( __( 'Author not found.', 'author-profile-blocks' ) );
		}

		// Generate styles for the block.
		$styles          = $this->get_block_styles( $attributes );
		$style_attribute = '';

		if ( ! empty( $styles ) ) {
			$style_attribute = $this->get_styles_string( $styles );
		}

		// Classes for the block wrapper.
		$wrapper_attributes = get_block_wrapper_attributes(
			array(
				'class' => $this->get_block_classes( $attributes, 'profile' ),
				'style' => $style_attribute,
			)
		);

		// Build the HTML.
		$html = '<div ' . $wrapper_attributes . '>';

		// Add profile layout based on selected template.
		$layout = $attributes['layout'] ?? 'default';
		switch ( $layout ) {
			case 'compact':
				$html .= $this->render_compact_layout( $author_data, $attributes );
				break;

			case 'card':
				$html .= $this->render_card_layout( $author_data, $attributes );
				break;

			case 'centered':
				$html .= $this->render_centered_layout( $author_data, $attributes );
				break;

			case 'default':
			default:
				$html .= $this->render_default_layout( $author_data, $attributes );
				break;
		}

		// Add optional more content if enabled.
		if ( ! empty( $attributes['showMoreContent'] ) && ! empty( $attributes['moreContent'] ) ) {
			$html .= $this->render_more_content( $attributes['moreContent'] );
		}

		$html .= '</div>';

		// Cache the result.
		$this->set_cached_render( $cache_key, $html );

		return $html;
	}

	/**
	 * Render default author profile layout.
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	private function render_default_layout( array $author, array $attributes ): string {
		$html = '<div class="apb-author-profile-content">';

		// Author image - only if image display is enabled in attributes.
		if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) {
			$html .= $this->render_author_image( $author );
		}

		// Author info.
		$html .= '<div class="apb-author-info">';

		// Author name.
		if ( ! empty( $author['title'] ) ) {
			$html .= $this->render_author_name( $author );
		}

		// Author position if available and display is enabled.
		if ( ! empty( $author['position'] ) && ( ! isset( $attributes['showPosition'] ) || $attributes['showPosition'] ) ) {
			$html .= $this->render_author_position( $author );
		}

		// Author email - only if email display is enabled in attributes.
		if ( ! empty( $author['email'] ) && ( ! isset( $attributes['showEmail'] ) || $attributes['showEmail'] ) ) {
			$html .= $this->render_author_email( $author );
		}

		// Registration date - only if registered date display is enabled in attributes.
		if ( ! empty( $author['registered_date'] ) && ( ! isset( $attributes['showRegisteredDate'] ) || $attributes['showRegisteredDate'] ) ) {
			$html .= $this->render_registered_date( $author );
		}

		// Author description - only if description display is enabled in attributes.
		if ( ! empty( $author['description'] ) && ( ! isset( $attributes['showDescription'] ) || $attributes['showDescription'] ) ) {
			$html .= $this->render_author_description( $author );
		}

		// Social profiles - only if display is enabled in attributes.
		if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ( ! isset( $attributes['showSocial'] ) || $attributes['showSocial'] ) ) {
			$html .= $this->render_social_profiles( $author['social'] );
		}

		$html .= '</div>'; // Close .apb-author-info.
		$html .= '</div>'; // Close .apb-author-profile-content.

		return $html;
	}
}
