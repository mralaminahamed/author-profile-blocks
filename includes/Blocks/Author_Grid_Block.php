<?php
declare(strict_types=1);
 // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Grid Block class
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Blocks;

use AuthorProfileBlocks\Blocks\Author_Block_Base;
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
	 * @param array<string, mixed> $attributes Block attributes.
	 * @param string               $content    Block content.
	 * @param WP_Block             $block      Block instance.
	 *
	 * @return string Rendered block output.
	 */
	public function render_callback( array $attributes, string $content, $block ): string {
		$author_ids = $this->extract_author_ids( $attributes );

		if ( empty( $author_ids ) ) {
			return $this->render_error_message( sprintf( $this->get_no_authors_selected_message(), 'grid' ) );
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
				'class' => $this->get_block_classes( $attributes, 'grid' ),
				'style' => $style_attribute,
			)
		);

		// Build the HTML using template.
		ob_start();
		author_profile_blocks()->get_template(
			'blocks/author-grid/grid.php',
			array(
				'authors'            => $authors,
				'attributes'         => $attributes,
				'block_instance'     => $this,
				'wrapper_attributes' => $wrapper_attributes,
			)
		);
		$html = ob_get_clean();

		// Ensure we have valid HTML content
		$content = $html !== false ? $html : '';

		// Cache the result.
		$this->set_cached_render( $cache_key, $content );

		return $content;
	}

	/**
	 * Render an individual author item within the grid.
	 *
	 * @param array<string, mixed> $author     Author data.
	 * @param array<string, mixed> $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	public function render_author_item( array $author, array $attributes ): string {
		// Pre-render all HTML content to avoid calling protected methods from templates.
		$item_styles     = $this->get_item_styles( $attributes );
		$style_attribute = '';

		if ( ! empty( $item_styles ) ) {
			$style_attribute = ' style="' . esc_attr( $this->get_styles_string( $item_styles ) ) . '"';
		}

		// Item classes based on layout and options.
		$item_classes = array( 'apbl-author-grid-item' );

		// Add layout class.
		$layout         = $attributes['layout'] ?? 'card';
		$item_classes[] = 'is-layout-' . $layout;

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

		$item_class = esc_attr( implode( ' ', $item_classes ) );

		// Pre-render author content.
		$author_image       = $this->render_author_image( $author, '', $attributes );
		$author_name        = $this->render_author_name( $author );
		$author_position    = $this->render_author_position( $author );
		$author_email       = $this->render_author_email( $author );
		$registered_date    = $this->render_registered_date( $author );
		$author_description = $this->render_author_description( $author );
		$social_links       = ! empty( $author['social'] ) && is_array( $author['social'] ) && ! empty( $attributes['showSocial'] )
			? $this->render_social_profiles( $author['social'] )
			: '';

		ob_start();
		author_profile_blocks()->get_template(
			'blocks/author-grid/item.php',
			array(
				'author'             => $author,
				'attributes'         => $attributes,
				'item_class'         => $item_class,
				'style_attribute'    => $style_attribute,
				'layout'             => $layout,
				'author_image'       => $author_image,
				'author_name'        => $author_name,
				'author_position'    => $author_position,
				'author_email'       => $author_email,
				'registered_date'    => $registered_date,
				'author_description' => $author_description,
				'social_links'       => $social_links,
				'block_instance'     => $this,
			)
		);
		$content = ob_get_clean();
		return $content !== false ? $content : '';
	}
}
