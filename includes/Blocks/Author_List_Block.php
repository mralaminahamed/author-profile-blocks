<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author List Block class
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

namespace AuthorProfileBlocks\Blocks;

use AuthorProfileBlocks\Blocks\Author_Block_Base;
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
		$author_ids = $this->extract_author_ids( $attributes );

		if ( empty( $author_ids ) ) {
			return $this->render_error_message( sprintf( $this->get_no_authors_selected_message(), 'list' ) );
		}

		// Check cache first.
		$cache_key      = $this->generate_cache_key( $author_ids, $attributes );
		$cached_content = $this->get_cached_render( $cache_key );
		if ( $cached_content ) {
			return $cached_content;
		}

		// Apply author role filter if specified.
		$roles = $this->extract_author_roles( $attributes );

		// Get authors data.
		$authors = $this->get_authors_data( $author_ids, $roles );

		// Apply maximum authors limit if specified.
		$max_authors = $this->extract_max_authors( $attributes );
		$authors     = $this->apply_author_limit( $authors, $max_authors );

		// If no authors found after filtering.
		if ( empty( $authors ) ) {
			return $this->render_error_message( $this->get_no_authors_found_message() );
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
		ob_start();
		?>
		<div 
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() returns properly escaped HTML
		echo $wrapper_attributes;
		?>
		>
			<?php
			$this->load_template(
				'blocks/author-list/list.php',
				array(
					'authors'        => $authors,
					'attributes'     => $attributes,
					'block_instance' => $this,
				)
			);
			?>
		</div>
		<?php
		$html = ob_get_clean();

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
	public function render_author_item( array $author, array $attributes ): string {
		// Get item styles.
		$item_styles     = $this->get_item_styles( $attributes );
		$style_attribute = '';

		if ( ! empty( $item_styles ) ) {
			$style_attribute = ' style="' . $this->get_styles_string( $item_styles ) . '"';
		}

		// Item classes based on layout and options.
		$item_classes = array( 'apbl-author-list-item' );

		// Add layout preset class.
		if ( ! empty( $attributes['layoutPreset'] ) ) {
			$item_classes[] = $attributes['layoutPreset'];
		}

		// Add animation classes.
		if ( ! empty( $attributes['animationType'] ) && $attributes['animationType'] !== 'none' ) {
			$item_classes[] = 'has-' . $attributes['animationType'] . '-animation';
		}

		// Add hover effect class.
		if ( ! empty( $attributes['hoverEffect'] ) && $attributes['hoverEffect'] !== 'none' ) {
			$item_classes[] = 'has-' . $attributes['hoverEffect'] . '-hover';
		}

		// Add Google Font class.
		if ( ! empty( $attributes['googleFont'] ) ) {
			$item_classes[] = 'has-' . sanitize_title( $attributes['googleFont'] ) . '-font';
		}

		// Add rounded class if enabled.
		if ( ! empty( $attributes['enableRounded'] ) ) {
			$item_classes[] = 'is-rounded';
		}

		// Add hover effect if enabled.
		if ( ! empty( $attributes['enableHoverEffect'] ) ) {
			$item_classes[] = 'has-hover-effect';
		}

		$item_class = esc_attr( implode( ' ', $item_classes ) );

		// Get the author content based on display style.
		$display_style = $attributes['displayStyle'] ?? 'compact';
		if ( 'detailed' === $display_style ) {
			$author_content = $this->render_detailed_layout( $author, $attributes );
		} else {
			$author_content = $this->render_compact_layout( $author, $attributes );
		}

		// Prepare template variables.
		$template_vars = array(
			'author'          => $author,
			'attributes'      => $attributes,
			'item_class'      => $item_class,
			'style_attribute' => $style_attribute,
			'display_style'   => $display_style,
			'author_content'  => $author_content,
		);

		// Start output buffering.
		ob_start();

		// Load the list item template.
		$this->load_template( 'blocks/list/item.php', $template_vars );

		// Return the buffered content.
		return ob_get_clean();
	}
}
