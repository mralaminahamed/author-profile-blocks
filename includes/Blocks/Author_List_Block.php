<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author List Block class
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
 * Class that handles the Author List block.
 */
class Author_List_Block extends Author_Block_Base {
	/**
	 * Get the block name.
	 *
	 * @return string Block name.
	 */
	public function get_block_name(): string {
		return 'author-list';
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
		$author_ids = $attributes['authorIds'] ?? array();

		if ( empty( $author_ids ) ) {
			return $this->render_error_message( __( 'Please select authors for the list.', 'author-profile-blocks' ) );
		}

		// Check cache first.
		$cache_key      = $this->generate_cache_key( $author_ids, $attributes );
		$cached_content = $this->get_cached_render( $cache_key );
		if ( $cached_content ) {
			return $cached_content;
		}

		// Apply author role filter if specified.
		$roles = array();
		if ( ! empty( $attributes['authorRole'] ) ) {
			$roles = array( $attributes['authorRole'] );
		}

		// Get authors data.
		$authors = $this->get_authors_data( $author_ids, $roles );

		// Apply maximum authors limit if specified.
		$max_authors = isset( $attributes['maxAuthors'] ) ? (int) $attributes['maxAuthors'] : 0;
		$authors     = $this->apply_author_limit( $authors, $max_authors );

		// If no authors found after filtering.
		if ( empty( $authors ) ) {
			return $this->render_error_message( __( 'No authors found matching the specified criteria.', 'author-profile-blocks' ) );
		}

		// Generate styles for the block.
		$wrapper_styles  = $this->get_block_styles( $attributes );
		$style_attribute = '';

		if ( ! empty( $wrapper_styles ) ) {
			$style_attribute = $this->get_styles_string( $wrapper_styles );
		}

		// Classes for the block wrapper.
		$wrapper_attributes = get_block_wrapper_attributes(
			array(
				'class' => $this->get_block_classes( $attributes, 'list' ),
				'style' => $style_attribute,
			)
		);

		// Build the HTML.
		$html = '<div ' . $wrapper_attributes . '>';

		// Determine list style.
		$list_style = $attributes['listStyle'] ?? 'ul';
		$list_tag   = ( 'ol' === $list_style ) ? 'ol' : 'ul';

		// Create list container with appropriate class.
		$list_class = 'apb-author-list';

		// Add divider class if enabled.
		if ( ! empty( $attributes['enableDividers'] ) ) {
			$list_class .= ' has-dividers';
		}

		// Add spacing style.
		$list_style_attr = '';
		if ( isset( $attributes['itemSpacing'] ) ) {
			$list_style_attr = ' style="gap: ' . (int) $attributes['itemSpacing'] . 'px;"';
		}

		$html .= '<' . $list_tag . ' class="' . esc_attr( $list_class ) . '"' . $list_style_attr . '>';

		// Add each author profile.
		foreach ( $authors as $author ) {
			$html .= $this->render_author_item( $author, $attributes );
		}

		$html .= '</' . $list_tag . '>'; // Close list container.
		$html .= '</div>'; // Close block wrapper.

		// Cache the result.
		$this->set_cached_render( $cache_key, $html );

		return $html;
	}

	/**
	 * Render an individual author item within the list.
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	private function render_author_item( array $author, array $attributes ): string {
		// Get item styles.
		$item_styles     = $this->get_item_styles( $attributes );
		$style_attribute = '';

		if ( ! empty( $item_styles ) ) {
			$style_attribute = ' style="' . $this->get_styles_string( $item_styles ) . '"';
		}

		// Item classes based on layout and options.
		$item_classes = array( 'apb-author-list-item' );

		// Add rounded class if enabled.
		if ( ! empty( $attributes['enableRounded'] ) ) {
			$item_classes[] = 'is-rounded';
		}

		// Add hover effect if enabled.
		if ( ! empty( $attributes['enableHoverEffect'] ) ) {
			$item_classes[] = 'has-hover-effect';
		}

		// Create list item.
		$html = '<li class="' . esc_attr( implode( ' ', $item_classes ) ) . '"' . $style_attribute . '>';

		// Inner content container.
		$html .= '<div class="apb-author-list-item-content">';

		// Layout depends on display style.
		$display_style = $attributes['displayStyle'] ?? 'compact';

		if ( 'detailed' === $display_style ) {
			$html .= $this->render_detailed_layout( $author, $attributes );
		} else {
			$html .= $this->render_compact_layout( $author, $attributes );
		}

		$html .= '</div>'; // Close .apb-author-list-item-content.
		$html .= '</li>'; // Close list item.

		return $html;
	}
}
