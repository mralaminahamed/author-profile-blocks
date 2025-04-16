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
use function get_post;
use function get_post_meta;
use function get_post_thumbnail_id;
use function get_the_title;
use function wp_get_attachment_image_url;

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
	public function get_block_name(): string {
		return 'author-profile';
	}

	/**
	 * Additional initialization actions for the block.
	 *
	 * @return void
	 */
	protected function additional_init(): void {
		// Register any block-specific assets or actions here.
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
			return '<div class="wpas-author-profile-error">Please select an author.</div>';
		}

		$author = $this->get_author_data( $author_id );

		if ( ! $author ) {
			return '<div class="wpas-author-profile-error">Author not found.</div>';
		}

		// Classes for the block wrapper.
		$wrapper_attributes = get_block_wrapper_attributes(
			array(
				'class' => $this->get_block_classes( $attributes ),
			)
		);

		// Build the HTML.
		$html  = '<div ' . $wrapper_attributes . '>';
		$html .= $this->render_author_content( $author, $attributes );

		// Add optional more content if enabled.
		if ( ! empty( $attributes['showMoreContent'] ) && ! empty( $attributes['moreContent'] ) ) {
			$html .= $this->render_more_content( $attributes['moreContent'] );
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Get author data from post ID.
	 *
	 * @param int $author_id Author post ID.
	 * @return array|null Author data or null if not found.
	 */
	private function get_author_data( int $author_id ): ?array {
		$author_post = get_post( $author_id );

		if ( ! $author_post instanceof WP_Post ) {
			return null;
		}

		$email        = get_post_meta( $author_id, 'author_email', true );
		$description  = get_post_meta( $author_id, 'author_description', true );
		$thumbnail_id = get_post_thumbnail_id( $author_id );
		$image_url    = '';

		if ( $thumbnail_id ) {
			$image_url = wp_get_attachment_image_url( $thumbnail_id, 'medium' );
		}

		return array(
			'id'          => $author_id,
			'name'        => get_the_title( $author_post ),
			'email'       => $email,
			'description' => $description,
			'image_url'   => $image_url,
		);
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
	 * Render author content.
	 *
	 * @param array $author Author data.
	 * @param array $attributes Block attributes.
	 * @return string Rendered HTML.
	 */
	private function render_author_content( array $author, array $attributes ): string {
		$html = '<div class="wpas-author-profile-content">';

		// Author image.
		if ( ! empty( $author['image_url'] ) ) {
			$html .= '<div class="wpas-author-image">';
			$html .= '<img src="' . esc_url( $author['image_url'] ) . '" alt="' . esc_attr( $author['name'] ) . '" />';
			$html .= '</div>';
		}

		// Author info.
		$html .= '<div class="wpas-author-info">';

		// Author name.
		if ( ! empty( $author['name'] ) ) {
			$html .= '<h3 class="wpas-author-name">' . esc_html( $author['name'] ) . '</h3>';
		}

		// Author email.
		if ( ! empty( $author['email'] ) ) {
			$html .= '<div class="wpas-author-email">';
			$html .= '<a href="mailto:' . esc_attr( $author['email'] ) . '">' . esc_html( $author['email'] ) . '</a>';
			$html .= '</div>';
		}

		// Author description.
		if ( ! empty( $author['description'] ) ) {
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
