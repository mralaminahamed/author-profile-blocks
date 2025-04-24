<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Grid Block class
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
 * Class that handles the Author Grid block.
 */
class Author_Grid_Block extends Author_Block_Base {
	/**
	 * Get the block name.
	 *
	 * @return string Block name.
	 */
	public function get_block_name(): string {
		return 'author-grid';
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
			return $this->render_error_message( __( 'Please select authors for the grid.', 'author-profile-blocks' ) );
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
				'class' => $this->get_block_classes( $attributes, 'grid' ),
				'style' => $style_attribute,
			)
		);

		// Build the HTML.
		$html = '<div ' . $wrapper_attributes . '>';

		// Create grid container with column class.
		$grid_class = 'apb-author-grid';
		if ( isset( $attributes['columns'] ) ) {
			$grid_class .= ' apb-columns-' . (int) $attributes['columns'];
		}

		// Add item spacing as inline style.
		$grid_style = '';
		if ( isset( $attributes['itemSpacing'] ) ) {
			$grid_style = 'gap: ' . (int) $attributes['itemSpacing'] . 'px;';
		}

		$html .= '<div class="' . esc_attr( $grid_class ) . '" style="' . esc_attr( $grid_style ) . '">';

		// Add each author profile.
		foreach ( $authors as $author ) {
			$html .= $this->render_author_item( $author, $attributes );
		}

		$html .= '</div>'; // Close grid container.
		$html .= '</div>'; // Close block wrapper.

		// Cache the result.
		$this->set_cached_render( $cache_key, $html );

		return $html;
	}

	/**
	 * Render an individual author item within the grid.
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
		$item_classes = array( 'apb-author-grid-item' );

		// Add layout class.
		$layout         = $attributes['layout'] ?? 'card';
		$item_classes[] = 'is-layout-' . $layout;

		// Add shadow class if enabled.
		if ( ! empty( $attributes['enableShadow'] ) ) {
			$item_classes[] = 'has-shadow';
		}

		// Add border class if enabled.
		if ( ! empty( $attributes['enableBorder'] ) ) {
			$item_classes[] = 'has-border';
		}

		// Add rounded class if enabled.
		if ( ! empty( $attributes['enableRounded'] ) ) {
			$item_classes[] = 'is-rounded';
		}

		// Build the author item.
		$html = '<div class="' . esc_attr( implode( ' ', $item_classes ) ) . '"' . $style_attribute . '>';

		// Use the appropriate layout template based on the selected layout.
		switch ( $layout ) {
			case 'compact':
				$html .= $this->render_compact_layout( $author, $attributes );
				break;

			case 'centered':
				$html .= $this->render_centered_layout( $author, $attributes );
				break;

			case 'card':
			default:
				$html .= $this->render_card_layout( $author, $attributes );
				break;
		}

		$html .= '</div>'; // Close grid item.

		return $html;
	}
}
