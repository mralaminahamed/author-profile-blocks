<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Author Grid Block class
 *
 * @package AuthorProfileBlocks
 * @subpackage Blocks
 */

namespace AuthorProfileBlocks\Blocks;

use WP_Block;
use AuthorProfileBlocks\Plugin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles the Author Grid block.
 */
class Author_Grid_Block extends Block_Base {
	/**
	 * Cache for rendered author grids to avoid duplicate processing.
	 *
	 * @var array
	 */
	private array $grid_cache = array();

	/**
	 * Get the block name.
	 *
	 * @return string Block name.
	 */
	public function get_block_name(): string {
		return 'author-grid';
	}

	/**
	 * Additional initialization actions for the block.
	 *
	 * @return void
	 */
	protected function additional_init(): void {
		add_filter( 'render_block_author-profile-blocks/author-grid', array( $this, 'filter_rendered_output' ), 10, 2 );
	}

	/**
	 * Filter the rendered output of the block.
	 * This is a final processing step after the block renders.
	 *
	 * @param string $block_content The rendered block content.
	 * @param array  $block         The block data.
	 *
	 * @return string The filtered content.
	 */
	public function filter_rendered_output( string $block_content, array $block ): string {
		/** Filters the rendered HTML output of the author grid block.
		 *
		 * This filter allows themes and plugins to modify the final HTML output
		 * of the author grid block before it is displayed on the page.
		 *
		 * @since 1.0.0
		 *
		 * @param string $block_content The rendered HTML content of the block.
		 * @param array  $block         The full block data including attributes and inner blocks.
		 */
		return apply_filters( 'author_profile_blocks_rendered_grid', $block_content, $block );
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
		$author_ids = $attributes['authorIds'] ?? array();

		if ( empty( $author_ids ) ) {
			return '<div class="apb-author-grid-error">' .
				esc_html__( 'Please select authors for the grid.', 'author-profile-blocks' ) .
				'</div>';
		}

		// Check cache first.
		$cache_key = $this->generate_cache_key( $author_ids, $attributes );
		if ( isset( $this->grid_cache[ $cache_key ] ) ) {
			return $this->grid_cache[ $cache_key ];
		}

		// Apply author role filter if specified.
		$roles = array();
		if ( ! empty( $attributes['authorRole'] ) ) {
			$roles = array( $attributes['authorRole'] );
		}

		// Get authors data using Plugin instance with role filtering.
		$authors = array();
		$plugin  = Plugin::get_instance();

		// Handle individual author IDs.
		foreach ( $author_ids as $author_id ) {
			$author_data = $plugin->get_author_data( $author_id );
			if ( $author_data ) {
				// Apply role filter if specified.
				if ( ! empty( $roles ) && ! in_array( $author_data['role'], $roles, true ) ) {
					continue;
				}
				$authors[] = $author_data;
			}
		}

		// Apply maximum authors limit if specified.
		$max_authors = isset( $attributes['maxAuthors'] ) ? (int) $attributes['maxAuthors'] : 0;
		if ( $max_authors > 0 && count( $authors ) > $max_authors ) {
			$authors = array_slice( $authors, 0, $max_authors );
		}

		// If no authors found after filtering.
		if ( empty( $authors ) ) {
			return '<div class="apb-author-grid-error">' .
				esc_html__( 'No authors found matching the specified criteria.', 'author-profile-blocks' ) .
				'</div>';
		}

		// Generate styles for the block.
		$wrapper_styles  = $this->get_block_styles( $attributes );
		$style_attribute = '';

		if ( ! empty( $wrapper_styles ) ) {
			$style_strings = array();
			foreach ( $wrapper_styles as $property => $value ) {
				$style_strings[] = $property . ': ' . $value;
			}
			$style_attribute = esc_attr( implode( '; ', $style_strings ) );
		}

		// Classes for the block wrapper.
		$wrapper_attributes = get_block_wrapper_attributes(
			array(
				'class' => $this->get_block_classes( $attributes ),
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
		$this->grid_cache[ $cache_key ] = $html;

		return $html;
	}

	/**
	 * Render an individual author item within the grid.
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 * @return string Rendered HTML.
	 */
	private function render_author_item( array $author, array $attributes ): string {
		// Get item styles.
		$item_styles     = $this->get_item_styles( $attributes );
		$style_attribute = '';

		if ( ! empty( $item_styles ) ) {
			$style_strings = array();
			foreach ( $item_styles as $property => $value ) {
				$style_strings[] = $property . ': ' . $value;
			}
			$style_attribute = ' style="' . esc_attr( implode( '; ', $style_strings ) ) . '"';
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

	/**
	 * Generate a cache key for the author grid based on attributes.
	 *
	 * @param array $author_ids The author IDs.
	 * @param array $attributes The block attributes.
	 * @return string The cache key.
	 */
	private function generate_cache_key( array $author_ids, array $attributes ): string {
		// Sort author IDs to ensure consistent cache key regardless of order.
		sort( $author_ids );
		return md5( implode( ',', $author_ids ) . maybe_serialize( $attributes ) );
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

		// Background color.
		if ( ! empty( $attributes['backgroundColor'] ) ) {
			$styles['background-color'] = $attributes['backgroundColor'];
		}

		return $styles;
	}

	/**
	 * Get styles for individual grid items.
	 *
	 * @param array $attributes Block attributes.
	 * @return array CSS styles.
	 */
	private function get_item_styles( array $attributes ): array {
		$styles = array();

		// Background color.
		if ( ! empty( $attributes['backgroundColor'] ) ) {
			$styles['background-color'] = $attributes['backgroundColor'];
		}

		// Padding.
		if ( isset( $attributes['padding'] ) ) {
			$styles['padding'] = $attributes['padding'] . 'px';
		}

		// Border color if enabled.
		if ( ! empty( $attributes['enableBorder'] ) && ! empty( $attributes['borderColor'] ) ) {
			$styles['border-color'] = $attributes['borderColor'];
		}

		// Border width if specified.
		if ( ! empty( $attributes['enableBorder'] ) && isset( $attributes['borderWidth'] ) ) {
			$styles['border-width'] = $attributes['borderWidth'] . 'px';
		}

		return $styles;
	}

	/**
	 * Render card author profile layout.
	 *
	 * @param array $author Author data.
	 * @param array $attributes Block attributes.
	 * @return string Rendered HTML.
	 */
	private function render_card_layout( array $author, array $attributes ): string {
		$html = '<div class="apb-author-profile-content apb-card">';

		// Card header.
		$html .= '<div class="apb-card-header">';

		// Author image - only if image display is enabled in attributes.
		if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) {
			$html .= $this->render_author_image( $author, 'apb-card-image' );
		}

		$html .= '</div>'; // Close .apb-card-header.

		// Card body.
		$html .= '<div class="apb-card-body">';

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

		$html .= '</div>'; // Close .apb-card-body.

		// Card footer with social profiles.
		if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ( ! isset( $attributes['showSocial'] ) || $attributes['showSocial'] ) ) {
			$html .= '<div class="apb-card-footer">';
			$html .= $this->render_social_profiles( $author['social'] );
			$html .= '</div>'; // Close .apb-card-footer.
		}

		$html .= '</div>'; // Close .apb-author-profile-content.

		return $html;
	}

	/**
	 * Render compact author profile layout.
	 *
	 * @param array $author Author data.
	 * @param array $attributes Block attributes.
	 * @return string Rendered HTML.
	 */
	private function render_compact_layout( array $author, array $attributes ): string {
		$html = '<div class="apb-author-profile-content">';

		// Author image and basic info in header row.
		$html .= '<div class="apb-author-header">';

		// Author image - only if image display is enabled in attributes.
		if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) {
			$html .= $this->render_author_image( $author );
		}

		// Author name and position wrapper.
		$html .= '<div class="apb-author-header-info">';

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

		$html .= '</div>'; // Close .apb-author-header-info.
		$html .= '</div>'; // Close .apb-author-header.

		// Author description in a separate row.
		if ( ! empty( $author['description'] ) && ( ! isset( $attributes['showDescription'] ) || $attributes['showDescription'] ) ) {
			$html .= $this->render_author_description( $author );
		}

		// Social profiles in footer row.
		if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ( ! isset( $attributes['showSocial'] ) || $attributes['showSocial'] ) ) {
			$html .= '<div class="apb-author-footer">';
			$html .= $this->render_social_profiles( $author['social'] );
			$html .= '</div>'; // Close .apb-author-footer.
		}

		$html .= '</div>'; // Close .apb-author-profile-content.

		return $html;
	}

	/**
	 * Render centered author profile layout.
	 *
	 * @param array $author Author data.
	 * @param array $attributes Block attributes.
	 * @return string Rendered HTML.
	 */
	private function render_centered_layout( array $author, array $attributes ): string {
		$html = '<div class="apb-author-profile-content apb-centered">';

		// Author image - only if image display is enabled in attributes.
		if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) {
			$html .= $this->render_author_image( $author, 'apb-centered-image' );
		}

		// Author info container.
		$html .= '<div class="apb-author-centered-info">';

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

		// Social profiles - only if display is enabled in attributes.
		if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ( ! isset( $attributes['showSocial'] ) || $attributes['showSocial'] ) ) {
			$html .= $this->render_social_profiles( $author['social'], 'apb-centered-social' );
		}

		$html .= '</div>'; // Close .apb-author-centered-info.

		// Author description - only if description display is enabled in attributes.
		if ( ! empty( $author['description'] ) && ( ! isset( $attributes['showDescription'] ) || $attributes['showDescription'] ) ) {
			$html .= $this->render_author_description( $author );
		}

		$html .= '</div>'; // Close .apb-author-profile-content.

		return $html;
	}

	/**
	 * Render author image section.
	 *
	 * @param array  $author Author data.
	 * @param string $wrapper_class Optional. Additional CSS class for the image container.
	 * @return string Rendered HTML.
	 */
	private function render_author_image( array $author, string $wrapper_class = '' ): string {
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
	 * @return string Rendered HTML.
	 */
	private function render_author_name( array $author ): string {
		return '<h3 class="apb-author-name">' . esc_html( $author['title'] ) . '</h3>';
	}

	/**
	 * Render author position section.
	 *
	 * @param array $author Author data.
	 * @return string Rendered HTML.
	 */
	private function render_author_position( array $author ): string {
		return '<div class="apb-author-position">' . esc_html( $author['position'] ) . '</div>';
	}

	/**
	 * Render author email section.
	 *
	 * @param array $author Author data.
	 * @return string Rendered HTML.
	 */
	private function render_author_email( array $author ): string {
		return '<div class="apb-author-email">' .
			'<a href="mailto:' . esc_attr( $author['email'] ) . '">' .
			esc_html( $author['email'] ) . '</a>' .
			'</div>';
	}

	/**
	 * Render author description section.
	 *
	 * @param array $author Author data.
	 * @return string Rendered HTML.
	 */
	private function render_author_description( array $author ): string {
		return '<div class="apb-author-description">' .
			wp_kses_post( $author['description'] ) .
			'</div>';
	}

	/**
	 * Render registered date section.
	 *
	 * @param array $author Author data.
	 * @return string Rendered HTML.
	 */
	private function render_registered_date( array $author ): string {
		// Use the customizable label.
		$label = $author['member_since_label'] ?? __( 'Member since', 'author-profile-blocks' );

		return '<div class="apb-author-registered-date">' .
			'<span class="apb-registered-date-label">' . esc_html( $label ) . '</span> ' .
			'<span class="apb-registered-date-value">' . esc_html( $author['registered_date'] ) . '</span>' .
			'</div>';
	}

	/**
	 * Render social profiles section.
	 *
	 * @param array  $profiles Social profile URLs.
	 * @param string $wrapper_class Optional. Additional CSS class for the social profiles container.
	 * @return string Rendered HTML.
	 */
	private function render_social_profiles( array $profiles, string $wrapper_class = '' ): string {
		$classes = 'apb-social-profiles';
		if ( ! empty( $wrapper_class ) ) {
			$classes .= ' ' . $wrapper_class;
		}

		$html  = '<div class="' . esc_attr( $classes ) . '">';
		$html .= '<ul class="apb-social-list">';

		$social_icons = array(
			'facebook'  => 'dashicons-facebook',
			'twitter'   => 'dashicons-twitter',
			'linkedin'  => 'dashicons-linkedin',
			'instagram' => 'dashicons-instagram',
			'website'   => 'dashicons-admin-site',
		);

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
}
