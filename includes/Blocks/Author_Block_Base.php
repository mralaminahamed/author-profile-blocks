<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Block Base
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

namespace AuthorProfileBlocks\Blocks;

use AuthorProfileBlocks\Core\Registerable;
use Author_Profile_Blocks;

/**
 * Abstract base class for author-related blocks.
 */
abstract class Author_Block_Base implements Registerable {
	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected string $block_name;

	/**
	 * Block constructor.
	 */
	public function __construct() {
		$this->block_name = $this->get_block_name();
	}

	/**
	 * Initialize the block.
	 *
	 * @return void
	 */
	public function init(): void {
		$this->register();
		$this->additional_init();
	}

	/**
	 * Get the block name.
	 *
	 * This is used both internally and externally to identify the block.
	 *
	 * @return string Block name.
	 */
	abstract public function get_block_name(): string;

	/**
	 * Registers the block using the metadata loaded from the `block.json` file.
	 *
	 * @return void
	 */
	public function register(): void {
		$block_path = APBL_PLUGIN_PATH . 'build/blocks/' . $this->block_name;

		// Check if block.json exists
		if ( ! file_exists( $block_path . '/block.json' ) ) {
			return;
		}

		$args = array();

		// If there's a render callback, add it to the args.
		$callback = $this->get_render_callback();
		if ( $callback ) {
			$args['render_callback'] = $callback;
		}

		// Apply custom block registration filters.
		$args = apply_filters( 'author_profile_blocks_block_args', $args, $this->block_name );

		// Register block using block.json metadata.
		$result = register_block_type( $block_path, $args );

		if ( ! $result ) {
			return;
		}

		// Trigger action after registration.
		do_action( 'author_profile_blocks_block_registered', $this->block_name, $this );
	}

	/**
	 * Additional initialization actions for the block.
	 *
	 * @return void
	 */
	protected function additional_init(): void {
		// Register filter for rendered output.
		add_filter( 'render_block_author-profile-blocks/' . $this->block_name, array( $this, 'filter_rendered_output' ), 10, 2 );

		// Add any block-specific initialization.
		$this->block_specific_init();
	}

	/**
	 * Get render callback for the block.
	 * Child classes should override this method if they need a callback.
	 *
	 * @return callable|null Block render callback or null.
	 */
	protected function get_render_callback(): ?callable {
		return null;
	}

	/**
	 * Block-specific initialization. Override in child classes if needed.
	 *
	 * @return void
	 */
	protected function block_specific_init(): void {
		// Override in child classes if needed.
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
		// Apply a block-specific filter first.
		$filtered_content = apply_filters(
			'author_profile_blocks_rendered_' . str_replace( '-', '_', $this->block_name ),
			$block_content,
			$block
		);

		// Then apply a general filter for all blocks.
		return apply_filters( 'author_profile_blocks_rendered_block', $filtered_content, $block, $this->block_name );
	}

	/**
	 * Handles error rendering for author blocks.
	 *
	 * @param string $message The error message.
	 *
	 * @return string HTML for error message.
	 */
	protected function render_error_message( string $message ): string {
		return '<div class="apbl-error-message">' . esc_html( $message ) . '</div>';
	}

	/**
	 * Get standardized error message for missing author selection.
	 *
	 * @return string The error message.
	 */
	protected function get_no_authors_selected_message(): string {
		/* translators: %s: block type (grid, carousel, list) */
		return __( 'Please select authors for the %s.', 'author-profile-blocks' );
	}

	/**
	 * Get standardized error message for no authors found.
	 *
	 * @return string The error message.
	 */
	protected function get_no_authors_found_message(): string {
		return __( 'No authors found matching the specified criteria.', 'author-profile-blocks' );
	}

	/**
	 * Get standardized error message for missing single author selection.
	 *
	 * @return string The error message.
	 */
	protected function get_no_author_selected_message(): string {
		return __( 'Please select an author.', 'author-profile-blocks' );
	}

	/**
	 * Get standardized error message for author not found.
	 *
	 * @return string The error message.
	 */
	protected function get_author_not_found_message(): string {
		return __( 'Author not found.', 'author-profile-blocks' );
	}

	/**
	 * Get author data from the service.
	 *
	 * @param int $author_id The author ID.
	 *
	 * @return array|null The author data or null if not found.
	 */
	protected function get_author_data( int $author_id ): ?array {
		if ( empty( $author_id ) ) {
			return null;
		}

		return Author_Profile_Blocks::get_instance()->get_author_data( $author_id );
	}

	/**
	 * Localize block script with common data.
	 *
	 * @param array $additional_data Optional additional data to include.
	 *
	 * @return void
	 */
	protected function localize_block_script( array $additional_data = array() ): void {
		$default_data = array(
			'adminUrl'  => admin_url(),
			'restNonce' => wp_create_nonce( 'wp_rest' ),
			'restUrl'   => rest_url(),
			'pluginUrl' => plugin_dir_url( APBL_PLUGIN_FILE ),
		);

		$localized_data = wp_parse_args( $additional_data, $default_data );

		wp_localize_script(
			'author-profile-blocks-' . $this->block_name . '-editor-script',
			'AuthorProfileBlocksData',
			$localized_data
		);
	}

	/**
	 * Extract author IDs from block attributes.
	 *
	 * @param array $attributes Block attributes.
	 *
	 * @return array Array of author IDs.
	 */
	protected function extract_author_ids( array $attributes ): array {
		return $attributes['authorIds'] ?? array();
	}

	/**
	 * Extract author roles from block attributes.
	 *
	 * @param array $attributes Block attributes.
	 *
	 * @return array Array of author roles.
	 */
	protected function extract_author_roles( array $attributes ): array {
		if ( ! empty( $attributes['authorRole'] ) ) {
			return array( $attributes['authorRole'] );
		}
		return array();
	}

	/**
	 * Extract max authors limit from block attributes.
	 *
	 * @param array $attributes Block attributes.
	 *
	 * @return int Maximum number of authors.
	 */
	protected function extract_max_authors( array $attributes ): int {
		return isset( $attributes['maxAuthors'] ) ? (int) $attributes['maxAuthors'] : 0;
	}

	/**
	 * Get multiple authors data from the service.
	 *
	 * @param array $author_ids Array of author IDs.
	 * @param array $roles      Optional. Roles to filter by.
	 * @param array $args       Optional. Additional arguments.
	 *
	 * @return array Array of author data.
	 */
	protected function get_authors_data( array $author_ids, array $roles = array(), array $args = array() ): array {
		if ( empty( $author_ids ) ) {
			return array();
		}

		$authors = array();
		$plugin  = Author_Profile_Blocks::get_instance();

		// First approach: Use individual author data method if we have specific IDs.
		foreach ( $author_ids as $author_id ) {
			$author_data = $plugin->get_author_data( $author_id );
			if ( $author_data ) {
				// Apply role filter if specified.
				if ( ! empty( $roles ) && ! empty( $author_data['role'] ) && ! in_array( $author_data['role'], $roles, true ) ) {
					continue;
				}
				$authors[] = $author_data;
			}
		}

		return $authors;
	}

	/**
	 * Apply maximum author limit.
	 *
	 * @param array $authors     Array of author data.
	 * @param int   $max_authors Maximum number of authors to include.
	 *
	 * @return array Limited author data.
	 */
	protected function apply_author_limit( array $authors, int $max_authors ): array {
		if ( $max_authors > 0 && count( $authors ) > $max_authors ) {
			return array_slice( $authors, 0, $max_authors );
		}

		return $authors;
	}

	/**
	 * Cache for rendered content.
	 *
	 * @var array
	 */
	protected array $render_cache = array();

	/**
	 * Get an item from the render cache.
	 *
	 * @param string $cache_key The cache key.
	 *
	 * @return string|null The cached content or null if not found.
	 */
	protected function get_cached_render( string $cache_key ): ?string {
		return isset( $this->render_cache[ $cache_key ] ) ? $this->render_cache[ $cache_key ] : null;
	}

	/**
	 * Set an item in the render cache.
	 *
	 * @param string $cache_key The cache key.
	 * @param string $content   The content to cache.
	 *
	 * @return void
	 */
	protected function set_cached_render( string $cache_key, string $content ): void {
		$this->render_cache[ $cache_key ] = $content;
	}

	/**
	 * Generate a cache key based on data and attributes.
	 *
	 * @param mixed $identifier A unique identifier (like author ID or array of IDs).
	 * @param array $attributes The block attributes.
	 *
	 * @return string The cache key.
	 */
	protected function generate_cache_key( $identifier, array $attributes ): string {
		if ( is_array( $identifier ) ) {
			// Sort IDs to ensure consistent cache key regardless of order.
			sort( $identifier );
			$id_string = implode( ',', $identifier );
		} else {
			$id_string = (string) $identifier;
		}

		return md5( $id_string . maybe_serialize( $attributes ) );
	}

	/**
	 * Get block classes based on attributes.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $block_type The block type identifier.
	 *
	 * @return string CSS classes.
	 */
	protected function get_block_classes( array $attributes, string $block_type = '' ): string {
		$classes = array();

		// Text alignment.
		if ( ! empty( $attributes['textAlign'] ) ) {
			$classes[] = 'has-text-align-' . $attributes['textAlign'];
		}

		// Add layout class.
		if ( ! empty( $attributes['layout'] ) ) {
			$classes[] = 'is-layout-' . $attributes['layout'];
		}

		// Add block style if specified
		if ( ! empty( $attributes['blockStyle'] ) ) {
			$classes[] = $attributes['blockStyle'];
		}

		// Add content order class
		if ( ! empty( $attributes['contentOrder'] ) ) {
			$classes[] = 'content-order-' . $attributes['contentOrder'];
		}

		// Add custom CSS class if specified
		if ( ! empty( $attributes['customCssClass'] ) ) {
			$classes[] = esc_attr( $attributes['customCssClass'] );
		}

		// Add display style for list/grid blocks.
		if ( ! empty( $attributes['displayStyle'] ) ) {
			$classes[] = 'is-style-' . $attributes['displayStyle'];
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

		if ( ! empty( $attributes['showSocialLinks'] ) ) {
			$classes[] = 'has-social-profiles';
		}

		if ( ! empty( $attributes['showMoreContent'] ) ) {
			$classes[] = 'has-more-content';
		}

		// Add avatar shape class if specified
		if ( ! empty( $attributes['avatarShape'] ) ) {
			$classes[] = 'avatar-shape-' . $attributes['avatarShape'];
		}

		// Add box shadow class if enabled
		if ( ! empty( $attributes['boxShadow'] ) ) {
			$classes[] = 'has-box-shadow';
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

		// Add hover effect class if enabled.
		if ( ! empty( $attributes['enableHoverEffect'] ) ) {
			$classes[] = 'has-hover-effect';
		}

		// Add block-specific class if provided.
		if ( ! empty( $block_type ) ) {
			$classes[] = 'is-block-' . $block_type;
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
	protected function get_block_styles( array $attributes ): array {
		$styles = array();

		// Background color.
		if ( ! empty( $attributes['backgroundColor'] ) ) {
			$styles['background-color'] = $attributes['backgroundColor'];
		}

		// Padding.
		if ( isset( $attributes['padding'] ) ) {
			$styles['padding'] = $attributes['padding'] . 'px';
		} elseif ( isset( $attributes['blockPadding'] ) ) {
			$styles['padding'] = $attributes['blockPadding'] . 'px';
		}

		// Margin
		if ( isset( $attributes['margin'] ) && ! empty( $attributes['margin'] ) ) {
			$styles['margin'] = $attributes['margin'];
		}

		// Width
		if ( isset( $attributes['containerWidth'] ) && ! empty( $attributes['containerWidth'] ) ) {
			$styles['width'] = $attributes['containerWidth'];
		}

		// Border styles
		if ( isset( $attributes['borderWidth'] ) && $attributes['borderWidth'] > 0 ) {
			$styles['border-width'] = $attributes['borderWidth'] . 'px';
			$styles['border-style'] = 'solid';

			if ( ! empty( $attributes['borderColor'] ) ) {
				$styles['border-color'] = $attributes['borderColor'];
			}
		}

		if ( isset( $attributes['borderRadius'] ) && $attributes['borderRadius'] > 0 ) {
			$styles['border-radius'] = $attributes['borderRadius'] . 'px';
		}

		// Box shadow
		if ( ! empty( $attributes['boxShadow'] ) ) {
			$h_offset = isset( $attributes['boxShadowHorizontal'] ) ? $attributes['boxShadowHorizontal'] : 0;
			$v_offset = isset( $attributes['boxShadowVertical'] ) ? $attributes['boxShadowVertical'] : 4;
			$blur     = isset( $attributes['boxShadowBlur'] ) ? $attributes['boxShadowBlur'] : 8;
			$spread   = isset( $attributes['boxShadowSpread'] ) ? $attributes['boxShadowSpread'] : 0;
			$color    = ! empty( $attributes['boxShadowColor'] ) ? $attributes['boxShadowColor'] : 'rgba(0,0,0,0.2)';

			$styles['box-shadow'] = $h_offset . 'px ' . $v_offset . 'px ' . $blur . 'px ' . $spread . 'px ' . $color;
		}

		// Section spacing custom property
		if ( isset( $attributes['sectionSpacing'] ) ) {
			$styles['--author-section-spacing'] = $attributes['sectionSpacing'] . 'px';
		}

		// Avatar custom properties
		if ( isset( $attributes['avatarSize'] ) ) {
			$styles['--author-avatar-size'] = $attributes['avatarSize'] . 'px';
		}

		if ( isset( $attributes['avatarBorderWidth'] ) ) {
			$styles['--author-avatar-border-width'] = $attributes['avatarBorderWidth'] . 'px';
		}

		if ( ! empty( $attributes['avatarBorderColor'] ) ) {
			$styles['--author-avatar-border-color'] = $attributes['avatarBorderColor'];
		}

		if ( ! empty( $attributes['avatarShape'] ) && $attributes['avatarShape'] === 'custom' && isset( $attributes['avatarBorderRadius'] ) ) {
			$styles['--author-avatar-border-radius'] = $attributes['avatarBorderRadius'] . 'px';
		}

		if ( ! empty( $attributes['avatarAlignment'] ) ) {
			$styles['--author-avatar-align'] = $attributes['avatarAlignment'];
		}

		if ( isset( $attributes['avatarMargin'] ) ) {
			$styles['--author-avatar-margin'] = $attributes['avatarMargin'] . 'px';
		}

		// Name custom properties
		if ( isset( $attributes['nameSize'] ) ) {
			$styles['--author-name-size'] = $attributes['nameSize'] . 'px';
		}

		if ( ! empty( $attributes['nameWeight'] ) ) {
			$styles['--author-name-weight'] = $attributes['nameWeight'];
		}

		if ( ! empty( $attributes['nameColor'] ) ) {
			$styles['--author-name-color'] = $attributes['nameColor'];
		}

		if ( ! empty( $attributes['nameTransform'] ) ) {
			$styles['--author-name-transform'] = $attributes['nameTransform'];
		}

		if ( ! empty( $attributes['nameAlignment'] ) ) {
			$styles['--author-name-align'] = $attributes['nameAlignment'];
		}

		if ( isset( $attributes['nameMargin'] ) ) {
			$styles['--author-name-margin'] = $attributes['nameMargin'] . 'px';
		}

		// Description custom properties
		if ( isset( $attributes['descriptionSize'] ) ) {
			$styles['--author-description-size'] = $attributes['descriptionSize'] . 'px';
		}

		if ( isset( $attributes['descriptionLineHeight'] ) ) {
			$styles['--author-description-line-height'] = $attributes['descriptionLineHeight'];
		}

		if ( ! empty( $attributes['descriptionColor'] ) ) {
			$styles['--author-description-color'] = $attributes['descriptionColor'];
		}

		if ( ! empty( $attributes['descriptionStyle'] ) ) {
			$styles['--author-description-style'] = $attributes['descriptionStyle'];
		}

		if ( ! empty( $attributes['descriptionAlignment'] ) ) {
			$styles['--author-description-align'] = $attributes['descriptionAlignment'];
		}

		if ( isset( $attributes['descriptionMargin'] ) ) {
			$styles['--author-description-margin'] = $attributes['descriptionMargin'] . 'px';
		}

		// Meta custom properties
		if ( isset( $attributes['metaSize'] ) ) {
			$styles['--author-meta-size'] = $attributes['metaSize'] . 'px';
		}

		if ( ! empty( $attributes['metaColor'] ) ) {
			$styles['--author-meta-color'] = $attributes['metaColor'];
		}

		if ( ! empty( $attributes['metaStyle'] ) ) {
			$styles['--author-meta-style'] = $attributes['metaStyle'];
		}

		if ( isset( $attributes['metaBold'] ) && $attributes['metaBold'] ) {
			$styles['--author-meta-weight'] = 'bold';
		}

		if ( ! empty( $attributes['metaAlignment'] ) ) {
			$styles['--author-meta-align'] = $attributes['metaAlignment'];
		}

		if ( isset( $attributes['metaMargin'] ) ) {
			$styles['--author-meta-margin'] = $attributes['metaMargin'] . 'px';
		}

		// Email link custom properties
		if ( ! empty( $attributes['emailLinkColor'] ) ) {
			$styles['--author-email-link-color'] = $attributes['emailLinkColor'];
		}

		if ( ! empty( $attributes['emailHoverColor'] ) ) {
			$styles['--author-email-link-hover-color'] = $attributes['emailHoverColor'];
		}

		// Social icon custom properties
		if ( isset( $attributes['socialIconSize'] ) ) {
			$styles['--author-social-icon-size'] = $attributes['socialIconSize'] . 'px';
		}

		if ( ! empty( $attributes['socialIconColor'] ) ) {
			$styles['--author-social-icon-color'] = $attributes['socialIconColor'];
		}

		if ( ! empty( $attributes['socialIconHoverColor'] ) ) {
			$styles['--author-social-icon-hover-color'] = $attributes['socialIconHoverColor'];
		}

		if ( ! empty( $attributes['socialIconBackground'] ) ) {
			$styles['--author-social-icon-bg'] = $attributes['socialIconBackground'];
		}

		if ( ! empty( $attributes['socialIconBackgroundHover'] ) ) {
			$styles['--author-social-icon-bg-hover'] = $attributes['socialIconBackgroundHover'];
		}

		if ( isset( $attributes['socialIconSpacing'] ) ) {
			$styles['--author-social-icon-spacing'] = $attributes['socialIconSpacing'] . 'px';
		}

		if ( ! empty( $attributes['socialIconAlignment'] ) ) {
			$styles['--author-social-icon-align'] = $attributes['socialIconAlignment'];
		}

		// More content custom properties
		if ( ! empty( $attributes['moreContentBorderColor'] ) ) {
			$styles['--author-more-content-border-color'] = $attributes['moreContentBorderColor'];
		}

		if ( isset( $attributes['moreContentPadding'] ) ) {
			$styles['--author-more-content-padding'] = $attributes['moreContentPadding'] . 'px';
		}

		// Custom CSS variables
		if ( ! empty( $attributes['customVar1'] ) ) {
			$styles['--author-profile-custom-var-1'] = $attributes['customVar1'];
		}

		if ( ! empty( $attributes['customVar2'] ) ) {
			$styles['--author-profile-custom-var-2'] = $attributes['customVar2'];
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
	 * Get styles for individual items like list items or grid items.
	 *
	 * @param array $attributes Block attributes.
	 *
	 * @return array CSS styles.
	 */
	protected function get_item_styles( array $attributes ): array {
		$styles = array();

		// Item background color.
		if ( ! empty( $attributes['itemBackgroundColor'] ) ) {
			$styles['background-color'] = $attributes['itemBackgroundColor'];
		}

		// Padding.
		if ( isset( $attributes['itemPadding'] ) ) {
			$styles['padding'] = $attributes['itemPadding'] . 'px';
		}

		// Border color if dividers enabled.
		if ( ! empty( $attributes['enableDividers'] ) && ! empty( $attributes['dividerColor'] ) ) {
			$styles['border-color'] = $attributes['dividerColor'];
		}

		return $styles;
	}

	/**
	 * Convert styles array to inline style string.
	 *
	 * @param array $styles Array of CSS property:value pairs.
	 *
	 * @return string CSS inline style string.
	 */
	protected function get_styles_string( array $styles ): string {
		if ( empty( $styles ) ) {
			return '';
		}

		$style_strings = array();
		foreach ( $styles as $property => $value ) {
			$style_strings[] = $property . ': ' . $value;
		}

		return implode( '; ', $style_strings );
	}

	/**
	 * Render compact layout for author profiles.
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_compact_layout( array $author, array $attributes ): string {
		$html = '<div class="apbl-author-compact">';

		// Author image - only if image display is enabled in attributes.
		if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) {
			$html .= $this->render_author_image( $author );
		}

		// Author info container.
		$html .= '<div class="apbl-author-info">';

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

		// Author description in compact mode might be truncated.
		if ( ! empty( $author['description'] ) && ( ! isset( $attributes['showDescription'] ) || $attributes['showDescription'] ) ) {
			$html .= $this->render_author_description( $author );
		}

		$html .= '</div>'; // Close .apbl-author-info.

		// Social icons if enabled.
		if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ( ! isset( $attributes['showSocial'] ) || $attributes['showSocial'] ) ) {
			$html .= '<div class="apbl-author-social">';
			$html .= $this->render_social_profiles( $author['social'], 'apbl-compact-social' );
			$html .= '</div>';
		}

		$html .= '</div>'; // Close .apbl-author-compact.

		return $html;
	}

	/**
	 * Render detailed layout for author profiles.
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_detailed_layout( array $author, array $attributes ): string {
		$html = '<div class="apbl-author-detailed">';

		// Left column with image.
		$html .= '<div class="apbl-author-left">';

		// Author image - only if image display is enabled in attributes.
		if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) {
			$html .= $this->render_author_image( $author );
		}

		$html .= '</div>'; // Close .apbl-author-left.

		// Right column with author details.
		$html .= '<div class="apbl-author-right">';

		// Author info header.
		$html .= '<div class="apbl-author-header">';

		// Author name.
		if ( ! empty( $author['title'] ) ) {
			$html .= $this->render_author_name( $author );
		}

		// Author position if available and display is enabled.
		if ( ! empty( $author['position'] ) && ( ! isset( $attributes['showPosition'] ) || $attributes['showPosition'] ) ) {
			$html .= $this->render_author_position( $author );
		}

		$html .= '</div>'; // Close .apbl-author-header.

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

		// Social profiles in footer if enabled.
		if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ( ! isset( $attributes['showSocial'] ) || $attributes['showSocial'] ) ) {
			$html .= '<div class="apbl-author-footer">';
			$html .= $this->render_social_profiles( $author['social'], 'apbl-detailed-social' );
			$html .= '</div>';
		}

		$html .= '</div>'; // Close .apbl-author-right.
		$html .= '</div>'; // Close .apbl-author-detailed.

		return $html;
	}

	/**
	 * Render card layout for author profiles.
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_card_layout( array $author, array $attributes ): string {
		$html = '<div class="apbl-author-card">';

		// Card header.
		$html .= '<div class="apbl-card-header">';

		// Author image - only if image display is enabled in attributes.
		if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) {
			$html .= $this->render_author_image( $author, 'apbl-card-image' );
		}

		$html .= '</div>'; // Close .apbl-card-header.

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

		$html .= '</div>'; // Close .apb-author-card.

		return $html;
	}

	/**
	 * Render centered layout for author profiles.
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_centered_layout( array $author, array $attributes ): string {
		$html = '<div class="apb-author-centered">';

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

		$html .= '</div>'; // Close .apb-author-centered.

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
	protected function render_author_image( array $author, string $wrapper_class = '' ): string {
		$classes = 'apb-author-image';
		if ( ! empty( $wrapper_class ) ) {
			$classes .= ' ' . $wrapper_class;
		}

		// Add alignment to the container class if specified
		if ( ! empty( $author['avatarAlignment'] ) ) {
			$classes .= ' apb-author-image-align-' . esc_attr( $author['avatarAlignment'] );
		}

		$image_classes = array();

		// Add avatar shape class if available from attributes
		if ( ! empty( $author['avatarShape'] ) ) {
			$image_classes[] = 'avatar-shape-' . esc_attr( $author['avatarShape'] );
		}

		// Build custom CSS for the avatar
		$avatar_styles = $this->get_avatar_inline_styles( $author );

		$image_attr = array(
			'class'   => ! empty( $image_classes ) ? implode( ' ', $image_classes ) : '',
			'alt'     => esc_attr( $author['title'] ),
			'loading' => 'lazy',
			'style'   => $avatar_styles,
		);

		return '<div class="' . esc_attr( $classes ) . '">' .
			wp_get_attachment_image(
				$author['image'],
				'full',
				false,
				$image_attr
			) .
		'</div>';
	}

	/**
	 * Generate inline styles for avatar image
	 *
	 * @param array $author Author data with style attributes
	 *
	 * @return string CSS inline style string
	 */
	private function get_avatar_inline_styles( array $author ): string {
		$styles = array();

		// Size
		if ( ! empty( $author['avatarSize'] ) ) {
			$styles[] = 'width: ' . esc_attr( $author['avatarSize'] ) . 'px';
			$styles[] = 'height: ' . esc_attr( $author['avatarSize'] ) . 'px';
		}

		// Border
		if ( ! empty( $author['avatarBorderWidth'] ) && $author['avatarBorderWidth'] > 0 ) {
			$styles[] = 'border-width: ' . esc_attr( $author['avatarBorderWidth'] ) . 'px';
			$styles[] = 'border-style: solid';

			if ( ! empty( $author['avatarBorderColor'] ) ) {
				$styles[] = 'border-color: ' . esc_attr( $author['avatarBorderColor'] );
			}
		}

		// Custom border radius for custom shape
		if ( ! empty( $author['avatarShape'] ) && $author['avatarShape'] === 'custom' && ! empty( $author['avatarBorderRadius'] ) ) {
			$styles[] = 'border-radius: ' . esc_attr( $author['avatarBorderRadius'] ) . 'px';
		} elseif ( ! empty( $author['avatarShape'] ) && $author['avatarShape'] === 'circle' ) {
			$styles[] = 'border-radius: 50%';
		} elseif ( ! empty( $author['avatarShape'] ) && $author['avatarShape'] === 'rounded' ) {
			$styles[] = 'border-radius: 8px';
		}

		// Margin
		if ( ! empty( $author['avatarMargin'] ) ) {
			$styles[] = 'margin-bottom: ' . esc_attr( $author['avatarMargin'] ) . 'px';
		}

		// Object fit to ensure proper sizing
		$styles[] = 'object-fit: cover';

		return implode( '; ', $styles );
	}

	/**
	 * Render author name section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_name( array $author ): string {
		$style_attr = $this->get_name_inline_styles( $author );
		$style_html = ! empty( $style_attr ) ? ' style="' . $style_attr . '"' : '';

		// Build the class attribute with alignment if specified
		$class_attr = 'apb-author-name';
		if ( ! empty( $author['nameAlignment'] ) ) {
			$class_attr .= ' has-text-align-' . esc_attr( $author['nameAlignment'] );
		}

		if ( isset( $author['url'] ) && '' !== $author['url'] ) {
			return sprintf(
				'<h3 class="%s"%s><a href="%s"%s>%s</a></h3>',
				esc_attr( $class_attr ),
				$style_html,
				esc_url( $author['url'] ),
				! empty( $style_attr ) ? ' style="color: inherit; text-decoration: none;"' : '',
				esc_html( $author['title'] )
			);
		}

		return sprintf(
			'<h3 class="%s"%s>%s</h3>',
			esc_attr( $class_attr ),
			$style_html,
			esc_html( $author['title'] )
		);
	}

	/**
	 * Generate inline styles for author name
	 *
	 * @param array $author Author data with style attributes
	 *
	 * @return string CSS inline style string
	 */
	private function get_name_inline_styles( array $author ): string {
		$styles = array();

		// Font size
		if ( ! empty( $author['nameSize'] ) ) {
			$styles[] = 'font-size: ' . esc_attr( $author['nameSize'] ) . 'px';
		}

		// Font weight
		if ( ! empty( $author['nameWeight'] ) ) {
			$styles[] = 'font-weight: ' . esc_attr( $author['nameWeight'] );
		}

		// Text color
		if ( ! empty( $author['nameColor'] ) ) {
			$styles[] = 'color: ' . esc_attr( $author['nameColor'] );
		}

		// Text transform
		if ( ! empty( $author['nameTransform'] ) && $author['nameTransform'] !== 'none' ) {
			$styles[] = 'text-transform: ' . esc_attr( $author['nameTransform'] );
		}

		// Text alignment
		if ( ! empty( $author['nameAlignment'] ) ) {
			$styles[] = 'text-align: ' . esc_attr( $author['nameAlignment'] );
		}

		// Margin
		if ( ! empty( $author['nameMargin'] ) ) {
			$styles[] = 'margin-bottom: ' . esc_attr( $author['nameMargin'] ) . 'px';
		}

		return implode( '; ', $styles );
	}

	/**
	 * Render author position section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_position( array $author ): string {
		$style_attr = $this->get_meta_inline_styles( $author );
		$style_html = ! empty( $style_attr ) ? ' style="' . $style_attr . '"' : '';

		// Build class attribute with alignment if specified
		$class_attr = 'apb-author-position';
		if ( ! empty( $author['metaAlignment'] ) ) {
			$class_attr .= ' has-text-align-' . esc_attr( $author['metaAlignment'] );
		}

		return sprintf(
			'<div class="%s"%s>%s</div>',
			esc_attr( $class_attr ),
			$style_html,
			esc_html( $author['position'] )
		);
	}

	/**
	 * Render author email section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_email( array $author ): string {
		$style_attr = $this->get_meta_inline_styles( $author );
		$style_html = ! empty( $style_attr ) ? ' style="' . $style_attr . '"' : '';

		// Build class attribute with alignment if specified
		$class_attr = 'apb-author-email';
		if ( ! empty( $author['metaAlignment'] ) ) {
			$class_attr .= ' has-text-align-' . esc_attr( $author['metaAlignment'] );
		}

		// Generate email link style
		$link_styles = array();

		if ( ! empty( $author['emailLinkColor'] ) ) {
			$link_styles[] = 'color: ' . esc_attr( $author['emailLinkColor'] );
		}

		$link_style_html = ! empty( $link_styles ) ? ' style="' . implode( '; ', $link_styles ) . '"' : '';

		// Add hover style via data attribute which will be handled by CSS
		$data_attr = '';
		if ( ! empty( $author['emailHoverColor'] ) ) {
			$data_attr = ' data-hover-color="' . esc_attr( $author['emailHoverColor'] ) . '"';
		}

		return sprintf(
			'<div class="%s"%s><a href="mailto:%s"%s%s>%s</a></div>',
			esc_attr( $class_attr ),
			$style_html,
			esc_attr( $author['email'] ),
			$link_style_html,
			$data_attr,
			esc_html( $author['email'] )
		);
	}

	/**
	 * Generate inline styles for meta elements (email, registered date)
	 *
	 * @param array $author Author data with style attributes
	 *
	 * @return string CSS inline style string
	 */
	private function get_meta_inline_styles( array $author ): string {
		$styles = array();

		// Font size
		if ( ! empty( $author['metaSize'] ) ) {
			$styles[] = 'font-size: ' . esc_attr( $author['metaSize'] ) . 'px';
		}

		// Text color
		if ( ! empty( $author['metaColor'] ) ) {
			$styles[] = 'color: ' . esc_attr( $author['metaColor'] );
		}

		// Font style
		if ( ! empty( $author['metaStyle'] ) && $author['metaStyle'] !== 'normal' ) {
			$styles[] = 'font-style: ' . esc_attr( $author['metaStyle'] );
		}

		// Font weight
		if ( ! empty( $author['metaBold'] ) ) {
			$styles[] = 'font-weight: bold';
		}

		// Text alignment
		if ( ! empty( $author['metaAlignment'] ) ) {
			$styles[] = 'text-align: ' . esc_attr( $author['metaAlignment'] );
		}

		// Margin
		if ( ! empty( $author['metaMargin'] ) ) {
			$styles[] = 'margin-bottom: ' . esc_attr( $author['metaMargin'] ) . 'px';
		}

		return implode( '; ', $styles );
	}

	/**
	 * Render author description section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_description( array $author ): string {
		$style_attr = $this->get_description_inline_styles( $author );
		$style_html = ! empty( $style_attr ) ? ' style="' . $style_attr . '"' : '';

		// Build class attribute with alignment if specified
		$class_attr = 'apb-author-description';
		if ( ! empty( $author['descriptionAlignment'] ) ) {
			$class_attr .= ' has-text-align-' . esc_attr( $author['descriptionAlignment'] );
		}

		return sprintf(
			'<div class="%s"%s>%s</div>',
			esc_attr( $class_attr ),
			$style_html,
			wp_kses_post( $author['description'] )
		);
	}

	/**
	 * Generate inline styles for author description
	 *
	 * @param array $author Author data with style attributes
	 *
	 * @return string CSS inline style string
	 */
	private function get_description_inline_styles( array $author ): string {
		$styles = array();

		// Font size
		if ( ! empty( $author['descriptionSize'] ) ) {
			$styles[] = 'font-size: ' . esc_attr( $author['descriptionSize'] ) . 'px';
		}

		// Line height
		if ( ! empty( $author['descriptionLineHeight'] ) ) {
			$styles[] = 'line-height: ' . esc_attr( $author['descriptionLineHeight'] );
		}

		// Text color
		if ( ! empty( $author['descriptionColor'] ) ) {
			$styles[] = 'color: ' . esc_attr( $author['descriptionColor'] );
		}

		// Font style
		if ( ! empty( $author['descriptionStyle'] ) && $author['descriptionStyle'] !== 'normal' ) {
			$styles[] = 'font-style: ' . esc_attr( $author['descriptionStyle'] );
		}

		// Text alignment
		if ( ! empty( $author['descriptionAlignment'] ) ) {
			$styles[] = 'text-align: ' . esc_attr( $author['descriptionAlignment'] );
		}

		// Margin
		if ( ! empty( $author['descriptionMargin'] ) ) {
			$styles[] = 'margin-bottom: ' . esc_attr( $author['descriptionMargin'] ) . 'px';
		}

		return implode( '; ', $styles );
	}

	/**
	 * Render social profiles section.
	 *
	 * @param array  $profiles      Social profile URLs.
	 * @param string $wrapper_class Optional. Additional CSS class for the social profiles container.
	 * @param array  $show_profiles Optional. List of specific profiles to show.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_social_profiles( array $profiles, string $wrapper_class = '', array $show_profiles = array() ): string {
		$classes = 'apb-social-profiles';
		if ( ! empty( $wrapper_class ) ) {
			$classes .= ' ' . $wrapper_class;
		}

		// Get social icon alignment if available
		if ( ! empty( $profiles['socialIconAlignment'] ) ) {
			$classes   .= ' apb-social-align-' . esc_attr( $profiles['socialIconAlignment'] );
			$data_align = ' data-align="' . esc_attr( $profiles['socialIconAlignment'] ) . '"';
		} else {
			$data_align = '';
		}

		// Generate styles for icons
		$icon_styles       = array();
		$icon_hover_styles = array();

		if ( ! empty( $profiles['socialIconSize'] ) ) {
			$icon_styles[] = '--author-social-icon-size: ' . esc_attr( $profiles['socialIconSize'] ) . 'px';
		}

		if ( ! empty( $profiles['socialIconColor'] ) ) {
			$icon_styles[] = '--author-social-icon-color: ' . esc_attr( $profiles['socialIconColor'] );
		}

		if ( ! empty( $profiles['socialIconHoverColor'] ) ) {
			$icon_hover_styles[] = '--author-social-icon-hover-color: ' . esc_attr( $profiles['socialIconHoverColor'] );
		}

		if ( ! empty( $profiles['socialIconBackground'] ) ) {
			$icon_styles[] = '--author-social-icon-bg: ' . esc_attr( $profiles['socialIconBackground'] );
		}

		if ( ! empty( $profiles['socialIconBackgroundHover'] ) ) {
			$icon_hover_styles[] = '--author-social-icon-bg-hover: ' . esc_attr( $profiles['socialIconBackgroundHover'] );
		}

		if ( ! empty( $profiles['socialIconSpacing'] ) ) {
			$icon_styles[] = '--author-social-icon-spacing: ' . esc_attr( $profiles['socialIconSpacing'] ) . 'px';
		}

		// Build style attribute
		$style_html = ! empty( $icon_styles ) ? ' style="' . implode( '; ', $icon_styles ) . '"' : '';
		$data_hover = ! empty( $icon_hover_styles ) ? ' data-hover-style="' . implode( '; ', $icon_hover_styles ) . '"' : '';

		$html  = '<div class="' . esc_attr( $classes ) . '"' . $data_align . $style_html . $data_hover . '>';
		$html .= '<ul class="apb-social-list">';

		$social_icons = $this->get_social_icons();

		// If specific profiles are specified, only show those
		$filtered_profiles = array();
		if ( ! empty( $show_profiles ) ) {
			foreach ( $profiles as $network => $url ) {
				if ( $network !== 'socialIconSize' &&
					$network !== 'socialIconColor' &&
					$network !== 'socialIconHoverColor' &&
					$network !== 'socialIconBackground' &&
					$network !== 'socialIconBackgroundHover' &&
					$network !== 'socialIconSpacing' &&
					$network !== 'socialIconAlignment' &&
					in_array( $network, $show_profiles, true ) ) {
					$filtered_profiles[ $network ] = $url;
				}
			}
		} else {
			// Filter out the style properties from profiles
			foreach ( $profiles as $network => $url ) {
				if ( $network !== 'socialIconSize' &&
					$network !== 'socialIconColor' &&
					$network !== 'socialIconHoverColor' &&
					$network !== 'socialIconBackground' &&
					$network !== 'socialIconBackgroundHover' &&
					$network !== 'socialIconSpacing' &&
					$network !== 'socialIconAlignment' ) {
					$filtered_profiles[ $network ] = $url;
				}
			}
		}

		foreach ( $filtered_profiles as $network => $url ) {
			if ( ! empty( $url ) && isset( $social_icons[ $network ] ) ) {
				$html .= '<li class="apb-social-item apb-social-' . esc_attr( $network ) . '">';
				$html .= '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">';
				$html .= '<span class="dashicons ' . esc_attr( $social_icons[ $network ] ) . '" aria-hidden="true"></span>';
				$html .= '<span class="screen-reader-text">' . esc_html( ucfirst( $network ) ) . '</span>';
				$html .= '</a>';
				$html .= '</li>';
			}
		}

		$html .= '</ul></div>';

		return $html;
	}

	/**
	 * Render registered date section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_registered_date( array $author ): string {
		// Use the customizable label.
		$label = $author['member_since_label'] ?? __( 'Member since', 'author-profile-blocks' );

		$style_attr = $this->get_meta_inline_styles( $author );
		$style_html = ! empty( $style_attr ) ? ' style="' . $style_attr . '"' : '';

		// Build class attribute with alignment if specified
		$class_attr = 'apb-author-registered-date';
		if ( ! empty( $author['metaAlignment'] ) ) {
			$class_attr .= ' has-text-align-' . esc_attr( $author['metaAlignment'] );
		}

		return sprintf(
			'<div class="%s"%s><span class="apb-registered-date-label">%s</span> <span class="apb-registered-date-value">%s</span></div>',
			esc_attr( $class_attr ),
			$style_html,
			esc_html( $label ),
			esc_html( $author['registered_date'] )
		);
	}

	/**
	 * Render more content section.
	 *
	 * @param string $content The content.
	 * @param array  $author  Optional. Author data with style attributes.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_more_content( string $content, array $author = array() ): string {
		if ( '' === $content ) {
			return '';
		}

		$styles = array();

		// Add border color if specified
		if ( ! empty( $author['moreContentBorderColor'] ) ) {
			$styles[] = 'border-top-color: ' . esc_attr( $author['moreContentBorderColor'] );
		}

		// Add top padding if specified
		if ( ! empty( $author['moreContentPadding'] ) ) {
			$styles[] = 'padding-top: ' . esc_attr( $author['moreContentPadding'] ) . 'px';
		}

		$style_html = ! empty( $styles ) ? ' style="' . implode( '; ', $styles ) . '"' : '';

		return sprintf(
			'<div class="apb-author-more-content"%s>%s</div>',
			$style_html,
			wp_kses_post( $content )
		);
	}

	/**
	 * Get social icon data.
	 *
	 * @return array Social icon data.
	 */
	protected function get_social_icons(): array {
		return array(
			'facebook'  => 'dashicons-facebook',
			'twitter'   => 'dashicons-twitter',
			'linkedin'  => 'dashicons-linkedin',
			'instagram' => 'dashicons-instagram',
			'website'   => 'dashicons-admin-site',
		);
	}

	/**
	 * Get social icon data for block editor.
	 *
	 * @return array Social icon data.
	 */
	protected function get_social_icon_data(): array {
		return array(
			'facebook'  => array(
				'name' => esc_html__( 'Facebook', 'author-profile-blocks' ),
				'icon' => 'facebook',
			),
			'twitter'   => array(
				'name' => esc_html__( 'Twitter', 'author-profile-blocks' ),
				'icon' => 'twitter',
			),
			'linkedin'  => array(
				'name' => esc_html__( 'LinkedIn', 'author-profile-blocks' ),
				'icon' => 'linkedin',
			),
			'instagram' => array(
				'name' => esc_html__( 'Instagram', 'author-profile-blocks' ),
				'icon' => 'instagram',
			),
			'website'   => array(
				'name' => esc_html__( 'Website', 'author-profile-blocks' ),
				'icon' => 'admin-site',
			),
		);
	}
}
