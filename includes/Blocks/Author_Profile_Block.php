<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Profile Block class
 *
 * @package    AuthorProfileBlocks
 * @subpackage Blocks
 */

namespace AuthorProfileBlocks\Blocks;

use AuthorProfileBlocks\Plugin;
use WP_Block;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles the Author Profile block.
 */
class Author_Profile_Block extends Block_Base {
	/**
	 * Cache for rendered author profiles to avoid duplicate processing.
	 *
	 * @var array
	 */
	private array $author_cache = array();

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
		add_action( 'enqueue_block_editor_assets', array( $this, 'localize_block_script' ) );
		add_filter( 'render_block_author-profile-blocks/author-profile', array( $this, 'filter_rendered_output' ), 10, 2 );
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
	 * Get social icon data for block editor.
	 *
	 * @return array Social icon data.
	 */
	private function get_social_icon_data(): array {
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

	/**
	 * Get render callback for the block.
	 *
	 * @return callable|null Block render callback.
	 */
	protected function get_render_callback(): ?callable {
		return array( $this, 'render_callback' );
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
		// Allow themes/plugins to modify the final output.
		return apply_filters( 'author_profile_blocks_rendered_profile', $block_content, $block );
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
			return '<div class="apb-author-profile-error">' .
			       esc_html__( 'Please select an author.', 'author-profile-blocks' ) .
			       '</div>';
		}

		// Check cache first.
		$cache_key = $this->generate_cache_key( $author_id, $attributes );
		if ( isset( $this->author_cache[ $cache_key ] ) ) {
			return $this->author_cache[ $cache_key ];
		}

		// Get author data using Plugin instance.
		$author_data = Plugin::get_instance()->get_author_data( $author_id );

		if ( ! $author_data ) {
			return '<div class="apb-author-profile-error">' .
			       esc_html__( 'Author not found.', 'author-profile-blocks' ) .
			       '</div>';
		}

		// Generate styles for the block.
		$styles          = $this->get_block_styles( $attributes );
		$style_attribute = '';

		if ( ! empty( $styles ) ) {
			$style_strings = array();
			foreach ( $styles as $property => $value ) {
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
		$this->author_cache[ $cache_key ] = $html;

		return $html;
	}

	/**
	 * Generate a cache key for the author profile based on attributes.
	 *
	 * @param int   $author_id  The author ID.
	 * @param array $attributes The block attributes.
	 *
	 * @return string The cache key.
	 */
	private function generate_cache_key( int $author_id, array $attributes ): string {
		return md5( $author_id . maybe_serialize( $attributes ) );
	}

	/**
	 * Get block classes based on attributes.
	 *
	 * @param array $attributes Block attributes.
	 *
	 * @return string CSS classes.
	 */
	private function get_block_classes( array $attributes ): string {
		$classes = array();

		// Text alignment.
		if ( ! empty( $attributes['textAlign'] ) ) {
			$classes[] = 'has-text-align-' . $attributes['textAlign'];
		}

		// Add layout class.
		if ( ! empty( $attributes['layout'] ) ) {
			$classes[] = 'is-layout-' . $attributes['layout'];
		} else {
			$classes[] = 'is-layout-default';
		}

		// Add classes based on display options.
		if ( ! empty( $attributes['showImage'] ) ) {
			$classes[] = 'has-author-image';
		}

		if ( ! empty( $attributes['showEmail'] ) ) {
			$classes[] = 'has-author-email';
		}

		if ( ! empty( $attributes['showDescription'] ) ) {
			$classes[] = 'has-author-description';
		}

		if ( ! empty( $attributes['showPosition'] ) ) {
			$classes[] = 'has-author-position';
		}

		if ( ! empty( $attributes['showRegisteredDate'] ) ) {
			$classes[] = 'has-registered-date';
		}

		if ( ! empty( $attributes['showSocial'] ) ) {
			$classes[] = 'has-social-profiles';
		}

		if ( ! empty( $attributes['showMoreContent'] ) ) {
			$classes[] = 'has-more-content';
		}

		// Add shadow class if enabled.
		if ( ! empty( $attributes['enableShadow'] ) ) {
			$classes[] = 'has-shadow';
		}

		// Add border class if enabled.
		if ( ! empty( $attributes['enableBorder'] ) ) {
			$classes[] = 'has-border';
		}

		// Add rounded class if enabled.
		if ( ! empty( $attributes['enableRounded'] ) ) {
			$classes[] = 'is-rounded';
		}

		return implode( ' ', $classes );
	}

	/**
	 * Get block styles based on attributes.
	 *
	 * @param array $attributes Block attributes.
	 *
	 * @return array CSS styles.
	 */
	private function get_block_styles( array $attributes ): array {
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

	/**
	 * Render compact author profile layout.
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
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
	 * Render card author profile layout.
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
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
	 * Render centered author profile layout.
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
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
	 * @param array  $author        Author data.
	 * @param string $wrapper_class Optional. Additional CSS class for the image container.
	 *
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
	 *
	 * @return string Rendered HTML.
	 */
	private function render_author_name( array $author ): string {
		return '<h3 class="apb-author-name">' . esc_html( $author['title'] ) . '</h3>';
	}

	/**
	 * Render author position section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	private function render_author_position( array $author ): string {
		return '<div class="apb-author-position">' . esc_html( $author['position'] ) . '</div>';
	}

	/**
	 * Render author email section.
	 *
	 * @param array $author Author data.
	 *
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
	 *
	 * @return string Rendered HTML.
	 */
	private function render_author_description( array $author ): string {
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
	private function render_social_profiles( array $profiles, string $wrapper_class = '' ): string {
		$classes = 'apb-social-profiles';
		if ( ! empty( $wrapper_class ) ) {
			$classes .= ' ' . $wrapper_class;
		}

		$html = '<div class="' . esc_attr( $classes ) . '">';
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

	/**
	 * Render registered date section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	private function render_registered_date( array $author ): string {
		// Use the customizable label.
		$label = isset( $author['member_since_label'] ) ? $author['member_since_label'] : __( 'Member since', 'author-profile-blocks' );

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
	private function render_more_content( string $content ): string {
		return '<div class="apb-author-more-content">' . wp_kses_post( $content ) . '</div>';
	}
}
