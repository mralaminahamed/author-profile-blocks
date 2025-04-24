<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Carousel Block class
 *
 * @package    AuthorProfileBlocks
 * @subpackage Blocks
 */

namespace AuthorProfileBlocks\Blocks;

use AuthorProfileBlocks\Common\Author_Block_Base;
use WP_Block;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles the Author Carousel block.
 */
class Author_Carousel_Block extends Author_Block_Base {
	/**
	 * Get the block name.
	 *
	 * @return string Block name.
	 */
	public function get_block_name(): string {
		return 'author-carousel';
	}

	/**
	 * Block-specific initialization.
	 *
	 * @return void
	 */
	protected function block_specific_init(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_carousel_dependencies' ) );
	}

	/**
	 * Register scripts and styles needed for the carousel
	 *
	 * @return void
	 */
	public function register_carousel_dependencies(): void {
		wp_register_script(
			'author-carousel-view',
			APB_PLUGIN_URL . 'build/blocks/author-carousel/view.js',
			array(),
			APB_VERSION,
			true
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
		$author_ids = $attributes['authorIds'] ?? array();

		if ( empty( $author_ids ) ) {
			return $this->render_error_message( __( 'Please select authors for the carousel.', 'author-profile-blocks' ) );
		}

		wp_enqueue_script( 'author-carousel-view' );

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
				'class' => $this->get_block_classes( $attributes, 'carousel' ),
				'style' => $style_attribute,
			)
		);

		// Build the HTML.
		$html = '<div ' . $wrapper_attributes . '>';

		// Create carousel container and prepare JSON settings for Slick initialization.
		$carousel_settings = array(
			'slidesToShow'   => isset( $attributes['slidesToShow'] ) ? (int) $attributes['slidesToShow'] : 3,
			'slidesToScroll' => 1,
			'autoplay'       => ! isset( $attributes['autoplay'] ) || (bool) $attributes['autoplay'],
			'autoplaySpeed'  => isset( $attributes['autoplaySpeed'] ) ? (int) $attributes['autoplaySpeed'] : 3000,
			'dots'           => ! isset( $attributes['showDots'] ) || (bool) $attributes['showDots'],
			'arrows'         => ! isset( $attributes['showArrows'] ) || (bool) $attributes['showArrows'],
			'infinite'       => ! isset( $attributes['infinite'] ) || (bool) $attributes['infinite'],
		);

		// Add carousel container.
		$html .= '<div class="apb-author-carousel" data-settings="' . esc_attr( wp_json_encode( $carousel_settings ) ) . '">';

		// Add each author slide.
		foreach ( $authors as $author ) {
			$html .= $this->render_author_slide( $author, $attributes );
		}

		$html .= '</div>'; // Close carousel container.
		$html .= '</div>'; // Close block wrapper.

		// Cache the result.
		$this->set_cached_render( $cache_key, $html );

		return $html;
	}

	/**
	 * Render an individual author slide within the carousel.
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	private function render_author_slide( array $author, array $attributes ): string {
		// Get item styles.
		$item_styles     = $this->get_item_styles( $attributes );
		$style_attribute = '';

		if ( ! empty( $item_styles ) ) {
			$style_attribute = ' style="' . $this->get_styles_string( $item_styles ) . '"';
		}

		// Item classes based on layout and options.
		$item_classes = array( 'apb-author-carousel-item' );

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

		// Build the author slide.
		$html = '<div class="apb-author-carousel-slide">';

		$html .= '<div class="' . esc_attr( implode( ' ', $item_classes ) ) . '"' . $style_attribute . '>';

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

		$html .= '</div>'; // Close carousel item.
		$html .= '</div>'; // Close carousel slide.

		return $html;
	}
}
