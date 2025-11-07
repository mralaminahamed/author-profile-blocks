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
	 * Load a template file with variables using WordPress native functions.
	 *
	 * This replaces wc_get_template() with WordPress native functionality.
	 *
	 * @param string $template_name Template name relative to templates directory.
	 * @param array  $args          Variables to extract for the template.
	 * @param string $template_path Base path for templates (defaults to plugin templates).
	 *
	 * @return void
	 */
	protected function load_template( string $template_name, array $args = array(), string $template_path = '' ): void {
		// Set default template path if not provided.
		if ( empty( $template_path ) ) {
			$template_path = plugin_dir_path( __FILE__ ) . '../../templates/';
		}

		// Build full template path.
		$template_file = $template_path . $template_name;

		// Check if template exists.
		if ( ! file_exists( $template_file ) ) {
			return;
		}

		// Extract variables for template use.
		if ( ! empty( $args ) ) {
			extract( $args, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		}

		// Include the template file.
		include $template_file;
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
	 * @return void
	 */
	public function localize_block_script(): void {
		$default_data = array(
			'adminUrl'    => admin_url(),
			'restNonce'   => wp_create_nonce( 'wp_rest' ),
			'restUrl'     => rest_url(),
			'pluginUrl'   => plugin_dir_url( APBL_PLUGIN_FILE ),
			'socialIcons' => $this->get_social_icon_data(),
		);

		$localized_data = apply_filters( 'author_profile_blocks_localized_block_data', $default_data );

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

		// Add layout preset class if specified
		if ( ! empty( $attributes['layoutPreset'] ) ) {
			$classes[] = $attributes['layoutPreset'];
		}

		// Add animation classes
		if ( ! empty( $attributes['animationType'] ) && $attributes['animationType'] !== 'none' ) {
			$classes[] = 'has-' . $attributes['animationType'] . '-animation';
		}

		// Add hover effect class
		if ( ! empty( $attributes['hoverEffect'] ) && $attributes['hoverEffect'] !== 'none' ) {
			$classes[] = 'has-' . $attributes['hoverEffect'] . '-hover';
		}

		// Add Google Font class
		if ( ! empty( $attributes['googleFont'] ) ) {
			$classes[] = 'has-' . sanitize_title( $attributes['googleFont'] ) . '-font';
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

		// Animation duration
		if ( isset( $attributes['animationDuration'] ) ) {
			$styles['--author-animation-duration'] = $attributes['animationDuration'] . 'ms';
		}

		// Transform properties
		if ( isset( $attributes['transformScale'] ) && $attributes['transformScale'] !== 1 ) {
			$styles['--author-transform-scale'] = $attributes['transformScale'];
		}

		if ( isset( $attributes['transformRotate'] ) && $attributes['transformRotate'] !== 0 ) {
			$styles['--author-transform-rotate'] = $attributes['transformRotate'] . 'deg';
		}

		// Filter properties
		if ( isset( $attributes['filterBrightness'] ) && $attributes['filterBrightness'] !== 100 ) {
			$styles['--author-filter-brightness'] = $attributes['filterBrightness'] . '%';
		}

		if ( isset( $attributes['filterContrast'] ) && $attributes['filterContrast'] !== 100 ) {
			$styles['--author-filter-contrast'] = $attributes['filterContrast'] . '%';
		}

		if ( isset( $attributes['filterSaturate'] ) && $attributes['filterSaturate'] !== 100 ) {
			$styles['--author-filter-saturate'] = $attributes['filterSaturate'] . '%';
		}

		// Gradient background
		if ( ! empty( $attributes['gradientBackground'] ) ) {
			$start_color = $attributes['gradientStartColor'] ?? '#ffffff';
			$end_color   = $attributes['gradientEndColor'] ?? '#f8f9fa';
			$direction   = $attributes['gradientDirection'] ?? 'to bottom';

			$styles['background'] = 'linear-gradient(' . $direction . ', ' . $start_color . ', ' . $end_color . ')';
		}

		// Custom CSS variables
		if ( isset( $attributes['customVar1'] ) && ! empty( $attributes['customVar1'] ) ) {
			$styles['--author-custom-var-1'] = $attributes['customVar1'];
		}

		if ( isset( $attributes['customVar2'] ) && ! empty( $attributes['customVar2'] ) ) {
			$styles['--author-custom-var-2'] = $attributes['customVar2'];
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

		// Animation duration for items
		if ( isset( $attributes['animationDuration'] ) ) {
			$styles['animation-duration'] = $attributes['animationDuration'] . 'ms';
		}

		// Transform scale
		if ( isset( $attributes['transformScale'] ) && $attributes['transformScale'] !== 1 ) {
			$styles['transform'] = 'scale(' . $attributes['transformScale'] . ')';
		}

		// Transform rotate
		if ( isset( $attributes['transformRotate'] ) && $attributes['transformRotate'] !== 0 ) {
			$current_transform   = $styles['transform'] ?? '';
			$rotate_transform    = 'rotate(' . $attributes['transformRotate'] . 'deg)';
			$styles['transform'] = $current_transform ? $current_transform . ' ' . $rotate_transform : $rotate_transform;
		}

		// Filter properties
		$filters = array();
		if ( isset( $attributes['filterBrightness'] ) && $attributes['filterBrightness'] !== 100 ) {
			$filters[] = 'brightness(' . $attributes['filterBrightness'] . '%)';
		}
		if ( isset( $attributes['filterContrast'] ) && $attributes['filterContrast'] !== 100 ) {
			$filters[] = 'contrast(' . $attributes['filterContrast'] . '%)';
		}
		if ( isset( $attributes['filterSaturate'] ) && $attributes['filterSaturate'] !== 100 ) {
			$filters[] = 'saturate(' . $attributes['filterSaturate'] . '%)';
		}
		if ( ! empty( $filters ) ) {
			$styles['filter'] = implode( ' ', $filters );
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
		// Prepare template variables.
		$template_vars = array(
			'author'             => $author,
			'attributes'         => $attributes,
			'author_image'       => $this->render_author_image( $author ),
			'author_name'        => $this->render_author_name( $author ),
			'author_position'    => $this->render_author_position( $author ),
			'author_email'       => $this->render_author_email( $author ),
			'author_description' => $this->render_author_description( $author ),
			'social_links'       => $this->render_social_profiles( $author['social'] ?? array(), 'apbl-compact-social' ),
		);

		// Start output buffering.
		ob_start();

		// Load the compact layout template.
		$this->load_template( 'blocks/layouts/compact.php', $template_vars );

		// Return the buffered content.
		return ob_get_clean();
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
		// Prepare template variables.
		$template_vars = array(
			'author'             => $author,
			'attributes'         => $attributes,
			'author_image'       => $this->render_author_image( $author ),
			'author_name'        => $this->render_author_name( $author ),
			'author_position'    => $this->render_author_position( $author ),
			'author_email'       => $this->render_author_email( $author ),
			'author_description' => $this->render_author_description( $author ),
			'registered_date'    => $this->render_registered_date( $author ),
			'social_links'       => $this->render_social_profiles( $author['social'] ?? array(), 'apbl-detailed-social' ),
		);

		// Start output buffering.
		ob_start();

		// Load the detailed layout template.
		$this->load_template( 'blocks/layouts/detailed.php', $template_vars );

		// Return the buffered content.
		return ob_get_clean();
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
		// Prepare template variables.
		$template_vars = array(
			'author'             => $author,
			'attributes'         => $attributes,
			'author_image'       => $this->render_author_image( $author, 'apbl-card-image' ),
			'author_name'        => $this->render_author_name( $author ),
			'author_position'    => $this->render_author_position( $author ),
			'author_email'       => $this->render_author_email( $author ),
			'author_description' => $this->render_author_description( $author ),
			'registered_date'    => $this->render_registered_date( $author ),
			'social_links'       => $this->render_social_profiles( $author['social'] ?? array() ),
		);

		// Start output buffering.
		ob_start();

		// Load the card layout template.
		$this->load_template( 'blocks/layouts/card.php', $template_vars );

		// Return the buffered content.
		return ob_get_clean();
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
		// Prepare template variables.
		$template_vars = array(
			'author'             => $author,
			'attributes'         => $attributes,
			'author_image'       => $this->render_author_image( $author, 'apbl-centered-image' ),
			'author_name'        => $this->render_author_name( $author ),
			'author_position'    => $this->render_author_position( $author ),
			'author_email'       => $this->render_author_email( $author ),
			'author_description' => $this->render_author_description( $author ),
			'registered_date'    => $this->render_registered_date( $author ),
			'social_links'       => $this->render_social_profiles( $author['social'] ?? array(), 'apbl-centered-social' ),
		);

		// Start output buffering.
		ob_start();

		// Load the centered layout template.
		$this->load_template( 'blocks/layouts/centered.php', $template_vars );

		// Return the buffered content.
		return ob_get_clean();
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
		// Prepare template variables.
		$template_vars = array(
			'author'           => $author,
			'attributes'       => array(), // Not used in this template
			'additional_class' => $wrapper_class,
		);

		// Start output buffering.
		ob_start();

		// Load the author image template.
		$this->load_template( 'blocks/components/author-image.php', $template_vars );

		// Return the buffered content.
		return ob_get_clean();
	}



	/**
	 * Render author name section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_name( array $author ): string {
		// Prepare template variables.
		$template_vars = array(
			'author'     => $author,
			'attributes' => array(), // Not used in this template
		);

		// Start output buffering.
		ob_start();

		// Load the author name template.
		$this->load_template( 'blocks/components/author-name.php', $template_vars );

		// Return the buffered content.
		return ob_get_clean();
	}



	/**
	 * Render author position section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_position( array $author ): string {
		// Prepare template variables.
		$template_vars = array(
			'author'     => $author,
			'attributes' => array(), // Not used in this template
		);

		// Start output buffering.
		ob_start();

		// Load the author position template.
		$this->load_template( 'blocks/components/author-position.php', $template_vars );

		// Return the buffered content.
		return ob_get_clean();
	}

	/**
	 * Render author email section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_email( array $author ): string {
		// Prepare template variables.
		$template_vars = array(
			'author'     => $author,
			'attributes' => array(), // Not used in this template
		);

		// Start output buffering.
		ob_start();

		// Load the author email template.
		$this->load_template( 'blocks/components/author-email.php', $template_vars );

		// Return the buffered content.
		return ob_get_clean();
	}



	/**
	 * Render author description section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_description( array $author ): string {
		// Prepare template variables.
		$template_vars = array(
			'author'     => $author,
			'attributes' => array(), // Not used in this template
		);

		// Start output buffering.
		ob_start();

		// Load the author description template.
		$this->load_template( 'blocks/components/author-description.php', $template_vars );

		// Return the buffered content.
		return ob_get_clean();
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
		// Prepare template variables.
		$template_vars = array(
			'social_profiles'  => $profiles,
			'additional_class' => $wrapper_class,
			'show_profiles'    => $show_profiles,
		);

		// Start output buffering.
		ob_start();

		// Load the social profiles template.
		$this->load_template( 'blocks/components/social-profiles.php', $template_vars );

		// Return the buffered content.
		return ob_get_clean();
	}

	/**
	 * Render registered date section.
	 *
	 * @param array $author Author data.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_registered_date( array $author ): string {
		// Prepare template variables.
		$template_vars = array(
			'author'     => $author,
			'attributes' => array(), // Not used in this template
		);

		// Start output buffering.
		ob_start();

		// Load the registered date template.
		$this->load_template( 'blocks/components/registered-date.php', $template_vars );

		// Return the buffered content.
		return ob_get_clean();
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

		// Prepare template variables.
		$template_vars = array(
			'content' => $content,
			'author'  => $author,
		);

		// Start output buffering.
		ob_start();

		// Load the more content template.
		$this->load_template( 'blocks/components/more-content.php', $template_vars );

		// Return the buffered content.
		return ob_get_clean();
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
